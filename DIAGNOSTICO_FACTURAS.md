# Diagnóstico de Discrepancia en Facturas - Cliente Nataly Barzola

## Problema Identificado
- **Panel del cliente muestra**: S/ 322.48
- **Factura real**: S/ 385.12
- **Diferencia**: S/ 62.64

## Posibles Causas

### 1. Facturas Duplicadas o Múltiples
Es posible que haya más de una factura para este cliente y el sistema esté sumando totales incorrectamente.

### 2. Facturas con estado "baja=1" 
Algunas facturas podrían estar marcadas como eliminadas pero aún se están contando en el cálculo.

### 3. Problema en el Cálculo del IVA
La diferencia de 62.64 es muy cercana al IVA de 53.12, sugiriendo un posible problema en el cálculo.

## Consultas SQL para Diagnosticar

Execute estas consultas en la base de datos para identificar el problema:

```sql
-- 1. Buscar cliente por nombre
SELECT id, nombres, apellidos, correo FROM clientes WHERE nombres LIKE '%Nataly%' OR apellidos LIKE '%Barzola%';

-- 2. Ver todas las órdenes del cliente (reemplazar X con el ID del cliente)
SELECT o.id, o.fechaIngreso, o.fechaSalida, o.estado, v.marca, v.modelo, v.anio 
FROM ordenreparacion o 
INNER JOIN vehiculos v ON o.idVehiculo = v.id 
WHERE v.idCliente = X AND o.baja = 0;

-- 3. Ver todas las facturas del cliente (reemplazar X con el ID del cliente)
SELECT f.id, f.idOrdenReparacion, f.manoObra, f.materiales, f.otro, f.iva, f.total, f.baja, f.alta_dt
FROM facturas f
INNER JOIN ordenreparacion o ON f.idOrdenReparacion = o.id
INNER JOIN vehiculos v ON o.idVehiculo = v.id
WHERE v.idCliente = X;

-- 4. Calcular total exacto como lo hace el sistema
SELECT COALESCE(SUM(f.total),0) AS gasto_total_calculado
FROM clientes c, vehiculos v, ordenreparacion o 
LEFT JOIN facturas f ON f.idOrdenReparacion=o.id AND f.baja=0 
WHERE c.id=X AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0;

-- 5. Ver facturas con problemas potenciales
SELECT f.*, (f.manoObra + f.materiales + f.otro + f.iva) AS total_calculado,
       (f.total - (f.manoObra + f.materiales + f.otro + f.iva)) AS diferencia
FROM facturas f
INNER JOIN ordenreparacion o ON f.idOrdenReparacion = o.id
INNER JOIN vehiculos v ON o.idVehiculo = v.id
WHERE v.idCliente = X;
```

## Soluciones Propuestas

### Solución 1: Limpiar Facturas Duplicadas
Si hay facturas duplicadas, eliminar las incorrectas:
```sql
-- Marcar facturas duplicadas como eliminadas (después de identificarlas)
UPDATE facturas SET baja = 1 WHERE id IN (lista_de_ids_duplicados);
```

### Solución 2: Recalcular Total de Factura
Si el total está mal calculado:
```sql
UPDATE facturas 
SET total = (manoObra + materiales + otro + iva) 
WHERE id = X;
```

### Solución 3: Verificar y Corregir IVA
Si el IVA está mal calculado:
```sql
UPDATE facturas 
SET iva = ((manoObra + materiales + otro) * 0.16), -- Asumiendo 16% de IVA
    total = (manoObra + materiales + otro + ((manoObra + materiales + otro) * 0.16))
WHERE id = X;
```

## Recomendación Inmediata

1. Ejecute las consultas de diagnóstico para identificar el problema exacto
2. Verifique si hay facturas múltiples para la misma orden
3. Confirme que todos los cálculos de IVA sean correctos
4. Una vez identificado el problema, aplique la solución correspondiente

## Archivo de Código Relacionado

El cálculo del gasto total se realiza en:
- **Archivo**: `app/modelos/TableroClienteModelo.php`
- **Método**: `getKpis()`
- **Línea**: 93
- **Consulta**: `SELECT COALESCE(SUM(f.total),0) AS s FROM clientes c, vehiculos v, ordenreparacion o LEFT JOIN facturas f ON f.idOrdenReparacion=o.id AND f.baja=0 WHERE c.id=X AND v.idCliente=c.id AND o.idVehiculo=v.id AND o.baja=0`