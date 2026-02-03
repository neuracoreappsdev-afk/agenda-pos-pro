# Sistema de Turnos - Integración Automática

## ✅ Cómo funciona ahora:

### 1. **Carga Automática desde la Base de Datos**

Cuando accedas a **Admin > Turnos**, el sistema automáticamente:

- **Carga especialistas** que tengan "manicur" en su título (ej: "Manicurista", "MANICURISTA", etc.)
- **Carga todos los servicios** disponibles en el sistema

### 2. **Cómo agregar especialistas para Turnos**

Ve a **Admin > Colaboradores > Crear Especialista** y:
- **Nombre**: Nombre del especialista (ej: "María González")
- **Título**: Debe contener "Manicurista" o "manicur" (ej: "Manicurista Profesional")
- **Avatar**: (Opcional) Foto del especialista

**Automáticamente aparecerá en Turnos** la próxima vez que recargues la página.

### 3. **Cómo agregar servicios para Turnos**

Ve a **Admin > Servicios** y agrega servicios normalmente:
- **Nombre**: Nombre del servicio (ej: "Manicure Gel")
- **Precio**: Precio del servicio
- **Tiempo**: Duración en minutos
- **Descripción**: Descripción del servicio

**Todos los servicios aparecerán automáticamente en Turnos**.

### 4. **Diseño Actualizado**

El módulo de Turnos ahora tiene:
- ✅ Colores alineados con el panel de admin (negro/gris)
- ✅ Fuente Inter para consistencia
- ✅ Sombras y bordes más sutiles
- ✅ Diseño más profesional y minimalista

### 5. **Funcionalidades que se mantienen**

- ✅ Cronómetros en tiempo real
- ✅ Drag & drop para reordenar colaboradoras
- ✅ Historial de servicios
- ✅ Dashboard con gráficos
- ✅ Exportar CSV/XML
- ✅ Control de día (iniciar/cerrar día)
- ✅ Cálculo de comisiones
- ✅ Todo guardado en LocalStorage

## 📋 Ejemplo de uso:

1. **Crear especialista**: Admin > Colaboradores > Crear
   - Nombre: "Ana López"
   - Título: "Manicurista Senior"

2. **Crear servicio**: Admin > Servicios > Crear
   - Nombre: "Manicure Básica"
   - Precio: 25000
   - Tiempo: 45 minutos

3. **Usar Turnos**: Admin > Turnos
   - Ana López aparecerá automáticamente
   - "Manicure Básica" estará en la lista de servicios
   - Selecciona el servicio y comienza a trackear tiempo

## ⚡ Ventajas:

- **Sin duplicación**: Un solo lugar para gestionar especialistas y servicios
- **Sincronización automática**: Nuevos especialistas/servicios aparecen automáticamente
- **Datos persistentes**: El historial de turnos se guarda en el navegador
- **Sin conflictos**: Si la BD no está disponible, funciona igual con datos locales
