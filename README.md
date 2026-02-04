# Simple A/B Testing

Plugin de WordPress para realizar pruebas A/B/N utilizando atributos de datos HTML, completamente compatible con bloques de Gutenberg.

## Descripción

Simple A/B Testing es un plugin ligero y potente que te permite realizar pruebas A/B/N en tu sitio de WordPress sin necesidad de herramientas externas ni dependencias complejas. Utiliza atributos de datos HTML (`data-*`) para configurar las pruebas y cookies para mantener la consistencia de la experiencia del usuario.

### Características Principales

- ✅ **Pruebas A/B/N**: Soporta cualquier número de variantes (A, B, C, D, etc.)
- ✅ **Persistencia de Usuario**: Las cookies mantienen la misma variante durante 30 días
- ✅ **DOM Limpio**: Elimina físicamente las variantes no seleccionadas (no solo las oculta)
- ✅ **Compatible con Gutenberg**: Funciona perfectamente con bloques HTML de Gutenberg
- ✅ **Sin Dependencias**: JavaScript vanilla, sin jQuery ni otras librerías
- ✅ **Múltiples Pruebas**: Ejecuta varias pruebas independientes en la misma página
- ✅ **Ligero y Rápido**: Código mínimo y optimizado
- ✅ **Seguimiento de Conversiones**: Rastrea automáticamente vistas y conversiones
- ✅ **Panel de Administración**: Visualiza estadísticas detalladas en WordPress
- ✅ **Exportación de Datos**: Exporta estadísticas en formato CSV y JSON
- ✅ **Almacenamiento en Base de Datos**: Persistencia de datos con tablas personalizadas de WordPress

## Instalación

1. Descarga el plugin y descomprímelo en el directorio `/wp-content/plugins/simple-ab-testing`
2. Activa el plugin desde el menú "Plugins" en WordPress
3. ¡Listo! El plugin está funcionando automáticamente

## Uso

### Estructura Básica

Para crear una prueba A/B, utiliza la siguiente estructura HTML en un bloque HTML personalizado de Gutenberg:

```html
<div data-ab-test="nombre-de-la-prueba">
  <div data-ab-variant="A">
    <!-- Contenido de la variante A -->
  </div>
  <div data-ab-variant="B">
    <!-- Contenido de la variante B -->
  </div>
</div>
```

### Ejemplo Completo con 3 Variantes

```html
<div data-ab-test="header-test">
  <div data-ab-variant="A">
    <h1>Título A</h1>
    <p>Descripción A</p>
    <button>Compra Ahora</button>
  </div>
  <div data-ab-variant="B">
    <h1>Título B</h1>
    <p>Descripción B</p>
    <button>Adquiérelo Ya</button>
  </div>
  <div data-ab-variant="C">
    <h1>Título C</h1>
    <p>Descripción C</p>
    <button>Consíguelo Hoy</button>
  </div>
</div>
```

### Ejemplo con Call-to-Action

```html
<div data-ab-test="cta-button">
  <div data-ab-variant="A">
    <button class="btn btn-primary">Regístrate Gratis</button>
  </div>
  <div data-ab-variant="B">
    <button class="btn btn-success">Prueba Gratuita</button>
  </div>
  <div data-ab-variant="C">
    <button class="btn btn-warning">Empieza Ahora</button>
  </div>
</div>
```

### Ejemplo con Hero Section Completo

```html
<div data-ab-test="hero-section">
  <div data-ab-variant="A">
    <section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <h1>Transforma Tu Negocio Hoy</h1>
      <p>Soluciones innovadoras para empresas modernas</p>
      <a href="/contacto" class="btn">Contáctanos</a>
    </section>
  </div>
  <div data-ab-variant="B">
    <section class="hero" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
      <h1>Impulsa Tu Crecimiento</h1>
      <p>Herramientas poderosas al alcance de tu mano</p>
      <a href="/demo" class="btn">Ver Demo</a>
    </section>
  </div>
  <div data-ab-variant="C">
    <section class="hero" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
      <h1>Alcanza Nuevas Metas</h1>
      <p>Software diseñado para el éxito</p>
      <a href="/registro" class="btn">Regístrate Gratis</a>
    </section>
  </div>
</div>
```

## Cómo Agregar en Gutenberg

1. **Crea una nueva página o entrada** en WordPress
2. **Agrega un bloque HTML personalizado**:
   - Haz clic en el botón "+" para agregar un bloque
   - Busca "HTML personalizado" o "Custom HTML"
   - Selecciona el bloque
3. **Pega tu código HTML** con los atributos `data-ab-test` y `data-ab-variant`
4. **Publica o actualiza** la página

## Seguimiento de Conversiones

El plugin incluye seguimiento automático de vistas y conversiones. Para rastrear cuando un usuario hace clic en un elemento (por ejemplo, un botón de compra), usa el atributo `data-ab-conversion`:

### Estructura con Conversión

```html
<div data-ab-test="header-test">
  <div data-ab-variant="A">
    <h1>Título A</h1>
    <p>Descripción A</p>
    <button data-ab-conversion="header-test">Compra Ahora</button>
  </div>
  <div data-ab-variant="B">
    <h1>Título B</h1>
    <p>Descripción B</p>
    <button data-ab-conversion="header-test">Adquiérelo Ya</button>
  </div>
  <div data-ab-variant="C">
    <h1>Título C</h1>
    <p>Descripción C</p>
    <button data-ab-conversion="header-test">Consíguelo Hoy</button>
  </div>
</div>
```

### Características del Seguimiento

- **Vistas Automáticas**: Se registra automáticamente cuando un usuario ve una variante
- **Conversiones por Clic**: Usa `data-ab-conversion="nombre-test"` en cualquier elemento clicable
- **Múltiples Conversiones**: Puedes tener varios elementos de conversión para el mismo test
- **Persistencia**: Los datos se almacenan en la base de datos de WordPress

### Ejemplo Completo con Conversión

```html
<div data-ab-test="landing-cta">
  <div data-ab-variant="A">
    <h2>Obtén tu prueba gratuita</h2>
    <p>Sin tarjeta de crédito requerida</p>
    <button class="btn btn-primary" data-ab-conversion="landing-cta">
      Empezar Gratis
    </button>
  </div>
  <div data-ab-variant="B">
    <h2>Comienza hoy mismo</h2>
    <p>Acceso instantáneo a todas las funciones</p>
    <button class="btn btn-success" data-ab-conversion="landing-cta">
      Regístrate Ahora
    </button>
  </div>
  <div data-ab-variant="C">
    <h2>Prueba sin riesgos</h2>
    <p>Cancela cuando quieras</p>
    <button class="btn btn-warning" data-ab-conversion="landing-cta">
      Activar Prueba
    </button>
  </div>
</div>
```

### Conversiones en Enlaces

También puedes rastrear conversiones en enlaces:

```html
<div data-ab-test="navbar-cta">
  <div data-ab-variant="A">
    <a href="/registro" data-ab-conversion="navbar-cta">Regístrate</a>
  </div>
  <div data-ab-variant="B">
    <a href="/registro" data-ab-conversion="navbar-cta">Crear Cuenta</a>
  </div>
</div>
```

## Panel de Administración

El plugin incluye un panel de administración completo donde puedes ver todas las estadísticas de tus pruebas A/B.

### Acceso al Panel

1. Inicia sesión en el administrador de WordPress
2. En el menú lateral, busca **"A/B Testing"** (tiene un icono de gráfica 📊)
3. Haz clic para ver tus estadísticas

### Información Mostrada

El panel muestra para cada prueba:

- **Nombre del Test**: El valor de `data-ab-test`
- **Variante**: A, B, C, etc.
- **Vistas**: Número de veces que se mostró cada variante
- **Conversiones**: Número de clics en elementos con `data-ab-conversion`
- **Tasa de Conversión**: Porcentaje calculado automáticamente (Conversiones / Vistas × 100)
- **Última Actualización**: Fecha y hora del último registro

### Ejemplo de Visualización

```
Test: header-test
┌──────────┬───────┬─────────────┬──────────────────┬──────────────────────┐
│ Variante │ Vistas│ Conversiones│ Tasa Conversión  │ Última Actualización │
├──────────┼───────┼─────────────┼──────────────────┼──────────────────────┤
│ A        │ 245   │ 45          │ 18.37%           │ 2026-02-04 12:30:15  │
│ B        │ 198   │ 52          │ 26.26%           │ 2026-02-04 12:31:42  │
│ C        │ 297   │ 71          │ 23.91%           │ 2026-02-04 12:32:08  │
└──────────┴───────┴─────────────┴──────────────────┴──────────────────────┘
Total: 740 vistas, 168 conversiones (22.70%)
```

### Análisis de Resultados

El panel te ayuda a identificar:

- ✅ **Variante Ganadora**: La que tiene mayor tasa de conversión
- 📊 **Volumen de Tráfico**: Cuántas personas han visto cada variante
- 🎯 **Rendimiento**: Qué mensajes/diseños funcionan mejor
- 📈 **Tendencias**: Cómo evoluciona cada variante con el tiempo

## Exportación de Datos

Puedes exportar todas tus estadísticas en dos formatos diferentes.

### Exportar CSV

1. Ve al panel de "A/B Testing" en WordPress
2. Haz clic en el botón **"Exportar CSV"**
3. Se descargará un archivo `.csv` con todos los datos

**Contenido del CSV:**
```csv
Test Name,Variant,Views,Conversions,Conversion Rate (%)
header-test,A,245,45,18.37
header-test,B,198,52,26.26
header-test,C,297,71,23.91
cta-button,A,156,34,21.79
cta-button,B,189,48,25.40
```

**Ideal para:**
- Análisis en Excel o Google Sheets
- Reportes para clientes
- Gráficas y visualizaciones personalizadas

### Exportar JSON

1. Ve al panel de "A/B Testing" en WordPress
2. Haz clic en el botón **"Exportar JSON"**
3. Se descargará un archivo `.json` con datos estructurados

**Contenido del JSON:**
```json
{
  "exported_at": "2026-02-04 15:30:00",
  "tests": [
    {
      "test_name": "header-test",
      "variants": [
        {
          "variant": "A",
          "views": 245,
          "conversions": 45,
          "conversion_rate": 18.37,
          "created_at": "2026-02-01 10:00:00",
          "updated_at": "2026-02-04 12:30:15"
        },
        {
          "variant": "B",
          "views": 198,
          "conversions": 52,
          "conversion_rate": 26.26,
          "created_at": "2026-02-01 10:00:00",
          "updated_at": "2026-02-04 12:31:42"
        }
      ],
      "totals": {
        "views": 443,
        "conversions": 97,
        "conversion_rate": 21.90
      }
    }
  ]
}
```

**Ideal para:**
- Integración con otras herramientas
- Análisis programático
- Dashboards personalizados
- APIs y webhooks

### Usos de las Exportaciones

1. **Análisis Profundo**: Importa los datos a herramientas de BI
2. **Reportes Automatizados**: Integra con sistemas de reporting
3. **Backup**: Guarda snapshots de tus pruebas
4. **Auditoría**: Mantén registro histórico de experimentos
5. **Presentaciones**: Crea gráficas para stakeholders

## Cómo Funcionan las Cookies

### Nombre de la Cookie

El plugin crea una cookie para cada prueba con el formato: `ab_test_{nombre-de-la-prueba}`

Por ejemplo:
- Para `data-ab-test="header-test"` → cookie: `ab_test_header-test`
- Para `data-ab-test="cta-button"` → cookie: `ab_test_cta-button`

### Duración

Las cookies tienen una duración de **30 días**. Esto significa que un usuario verá la misma variante durante 30 días consecutivos, garantizando una experiencia consistente.

### Selección de Variante

1. **Primera Visita**: Si el usuario no tiene una cookie para la prueba, el plugin selecciona aleatoriamente una variante y guarda la selección en una cookie.
2. **Visitas Posteriores**: El plugin lee la cookie y muestra la variante previamente asignada.

### Ejemplo de Cookie

Si un usuario ve la variante "B" del test "header-test", se creará:
- **Nombre**: `ab_test_header-test`
- **Valor**: `B`
- **Expiración**: 30 días desde la primera visita

## Base de Datos

El plugin crea automáticamente una tabla en la base de datos de WordPress al activarse:

### Estructura de la Tabla

**Nombre**: `{$wpdb->prefix}ab_testing_stats`

**Columnas**:
- `id` - Identificador único (auto-incremento)
- `test_name` - Nombre del test (de `data-ab-test`)
- `variant` - Variante (A, B, C, etc.)
- `views` - Número de vistas
- `conversions` - Número de conversiones
- `created_at` - Fecha de creación
- `updated_at` - Fecha de última actualización

**Índices**:
- Índice único en combinación de `test_name` + `variant`
- Índice en `test_name` para consultas rápidas

### Seguridad

- ✅ Todas las consultas usan `$wpdb->prepare()` para prevenir SQL injection
- ✅ Los datos se sanitizan antes de guardarse
- ✅ Los nonces de WordPress protegen las solicitudes AJAX
- ✅ Solo usuarios con permisos `manage_options` pueden exportar datos

## Múltiples Pruebas en la Misma Página

Puedes ejecutar varias pruebas A/B independientes en la misma página. Cada una debe tener un `data-ab-test` único:

```html
<!-- Prueba 1: Encabezado -->
<div data-ab-test="header-test">
  <div data-ab-variant="A">
    <h1>Encabezado Variante A</h1>
  </div>
  <div data-ab-variant="B">
    <h1>Encabezado Variante B</h1>
  </div>
</div>

<!-- Prueba 2: Botón CTA -->
<div data-ab-test="cta-button">
  <div data-ab-variant="A">
    <button>Compra Ahora</button>
  </div>
  <div data-ab-variant="B">
    <button>Adquirir</button>
  </div>
</div>

<!-- Prueba 3: Imagen -->
<div data-ab-test="hero-image">
  <div data-ab-variant="A">
    <img src="/imagen-a.jpg" alt="Imagen A">
  </div>
  <div data-ab-variant="B">
    <img src="/imagen-b.jpg" alt="Imagen B">
  </div>
</div>
```

## Integración con Herramientas Externas

Aunque el plugin incluye su propio sistema de seguimiento, también puedes integrarlo con herramientas externas de analytics.

### Ejemplo de Seguimiento con JavaScript

```javascript
// Obtener la variante del usuario para un test específico
function getABTestVariant(testName) {
  const cookieName = 'ab_test_' + testName;
  const cookies = document.cookie.split(';');
  for (let cookie of cookies) {
    const [name, value] = cookie.trim().split('=');
    if (name === cookieName) {
      return value;
    }
  }
  return null;
}

// Ejemplo de uso
const headerVariant = getABTestVariant('header-test');
console.log('Usuario ve variante:', headerVariant);

// Enviar a Google Analytics
if (typeof gtag !== 'undefined') {
  gtag('event', 'ab_test_view', {
    'test_name': 'header-test',
    'variant': headerVariant
  });
}
```

## Notas Técnicas

- **Ejecución Temprana**: El JavaScript se carga con estrategia `defer` para ejecutarse lo antes posible
- **Vanilla JavaScript**: No requiere jQuery ni otras dependencias
- **Eliminación del DOM**: Las variantes no seleccionadas se eliminan completamente del DOM (no solo se ocultan con CSS)
- **Compatibilidad**: Compatible con WordPress 5.0+ y todos los navegadores modernos

## Preguntas Frecuentes

### ¿Puedo usar más de 2 variantes?
Sí, puedes usar tantas variantes como necesites (A, B, C, D, E, etc.)

### ¿Las variantes eliminadas afectan el SEO?
No, ya que la selección de variantes ocurre en el navegador del usuario después de que el contenido se carga.

### ¿Cómo reinicio una prueba?
Simplemente elimina la cookie correspondiente o espera 30 días para que expire.

### ¿Funciona con bloques de Gutenberg?
Sí, funciona perfectamente con bloques HTML personalizados de Gutenberg.

### ¿Los datos se pueden exportar?
Sí, puedes exportar todas las estadísticas en formato CSV o JSON desde el panel de administración.

### ¿Cómo se rastrean las conversiones?
Usa el atributo `data-ab-conversion="nombre-test"` en cualquier elemento clicable (botones, enlaces, etc.). El plugin rastreará automáticamente los clics como conversiones.

### ¿Puedo ver estadísticas en tiempo real?
Sí, el panel de administración muestra todas las estadísticas actualizadas. Recarga la página del panel para ver los datos más recientes.

## Soporte y Contribuciones

Para reportar problemas o contribuir al desarrollo:
- GitHub: https://github.com/mattdlgado/simple-ab-testing

## Licencia

Este plugin está licenciado bajo GPL v2 o posterior.

## Changelog

### 1.1.0
- ✨ Nuevo panel de administración con estadísticas visuales
- ✨ Seguimiento automático de vistas y conversiones
- ✨ Almacenamiento de datos en base de datos de WordPress
- ✨ Exportación de datos en CSV y JSON
- ✨ Atributo `data-ab-conversion` para rastrear conversiones
- ✨ API AJAX para tracking en tiempo real
- 🔒 Mejoras de seguridad con nonces y sanitización
- 📚 Documentación completa actualizada

### 1.0.0
- Lanzamiento inicial
- Soporte para pruebas A/B/N
- Persistencia con cookies (30 días)
- Compatible con Gutenberg
