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

## Seguimiento de Resultados

Este plugin se enfoca en la asignación y persistencia de variantes. Para el seguimiento de conversiones y análisis, puedes:

1. **Google Analytics**: Usa eventos personalizados con la variante asignada
2. **Google Tag Manager**: Captura el atributo `data-ab-variant` del elemento visible
3. **Herramientas de Analytics**: Lee las cookies usando JavaScript

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

## Soporte y Contribuciones

Para reportar problemas o contribuir al desarrollo:
- GitHub: https://github.com/mattdlgado/simple-ab-testing

## Licencia

Este plugin está licenciado bajo GPL v2 o posterior.

## Changelog

### 1.0.0
- Lanzamiento inicial
- Soporte para pruebas A/B/N
- Persistencia con cookies (30 días)
- Compatible con Gutenberg
