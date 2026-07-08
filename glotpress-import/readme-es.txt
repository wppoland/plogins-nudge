=== Plogins Nudge - Free Shipping Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requiere complementos: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Una barra de progreso de envío gratuito que les indica a los compradores de WooCommerce cuánto más deben añadir para calificar para la entrega gratuita.

== Description ==

Nudge muestra a los compradores qué tan lejos está su carrito de su umbral de envío gratuito y
cuánto más necesitan añadir para alcanzarlo. El mensaje se actualiza a medida que el carrito
cambios, por lo que "envío gratis a partir de $50" deja de ser letra pequeña y se convierte en un número
el cliente puede actuar.

De forma predeterminada, el umbral proviene directamente de WooCommerce. Nudge mira tu
Habilitó métodos de envío gratuito en todas las zonas de envío y utiliza el precio más bajo.
cantidad mínima de pedido que encuentra, por lo que no mantiene la cifra en dos lugares. si
prefiere configurarlo tú mismo, cambiar al modo Manual y escribir una cantidad fija; eso
La cantidad también se utiliza como alternativa cuando el modo Automático no encuentra ningún método de calificación.

Cuando no hay nada útil que mostrar (la barra está deshabilitada, el carrito está vacío o no hay
umbral está configurado) Nudge no representa nada en lugar de un espacio vacío o
barra siempre terminada.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-nudge/docs/
* <strong>Página de complementos</strong> - https://plogins.com/es/plogins-nudge/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-nudge
* <strong>Informes de errores y solicitudes de funciones</strong> - https://github.com/wppoland/plogins-nudge/issues


= What it does =

* Lee el umbral de envío gratuito automáticamente desde tu WooCommerce activo
  métodos de envío gratuito o aceptan una cantidad fija que estableces manualmente.
* Se vuelve a representar con el carrito tanto en las páginas clásicas de Carrito/Pago como en la
  Bloques de carrito/pago. Un pequeño script (sin jQuery propio) anima el ancho
  entre actualizaciones.
* Expone un `role="progressbar"` real con `aria-valuenow`/`min`/`max` y un
  mensajes de texto legibles para lectores de pantalla; honra "prefiere movimiento reducido".
* Reserva la altura de la barra antes de pintar, por lo que agregarla no cambia el diseño.
* Diseña la barra con propiedades personalizadas CSS `--nudge-*` y se adapta al color oscuro
  esquemas, para que los temas puedan cambiar su color sin editar el marcado.
* Le permite escribir los mensajes de progreso y éxito, con un token `{amount}` en
  el mensaje de progreso para el total restante.
* Envía un archivo POT, elimina sus opciones de desinstalación y declara HPOS y
  Compatibilidad con carritos/bloques de pago.

El código fuente y los informes de errores están disponibles en GitHub: https://github.com/wppoland/plogins-nudge

== Installation ==

1. Cargue el complemento en `/wp-content/plugins/nudge`, o instálelo a través de Complementos → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Vaya a <strong>WooCommerce → Nudge</strong>, activa la barra y elija dónde se muestra.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Nudge no hace nada hasta que WooCommerce esté activo.

= Where does the free-shipping amount come from? =

En el modo Automático, Nudge lee el monto mínimo del pedido de su cuenta habilitada.
Métodos de envío gratuito de WooCommerce y utiliza el más pequeño en todos sus envíos.
zonas. En el modo Manual, tú mismo establece una cantidad fija. Si el modo automático no encuentra
método con un monto mínimo de pedido, en su lugar utiliza el monto manual.

= What shows when no free-shipping goal is configured? =

Nada. En lugar de representar una barra vacía o siempre completa, Nudge omite la salida
completamente hasta que haya un umbral real para realizar la cuenta regresiva.

= Does it work with the Cart and Checkout blocks? =

Sí. Se muestra en las plantillas clásicas de carrito/pago y en WooCommerce.
Bloques de carrito/pago y declara compatibilidad con HPOS y bloques de carrito/pago.

= Can I change the wording and colours? =

Sí. Los mensajes de progreso y éxito se pueden editar en la pantalla de configuración y
los colores y el tamaño de la barra son `--nudge-*` propiedades personalizadas de CSS que tu tema puede
anular.


= Does this plugin work on WordPress Multisite? =

Sí. Este complemento es compatible con WordPress Multisite. Activarlo en red o activarlo en sitios individuales; Cada sitio mantiene su propia configuración y datos.

== Screenshots ==

1. La barra de progreso de envío gratuito en el carrito.
2. La pantalla de configuración de Nudge.

== External Services ==

Nudge no se conecta a ningún servicio externo. No envía análisis, no registra una licencia, no carga fuentes o scripts remotos ni realiza ninguna solicitud HTTP desde su servidor. Todo lo que necesita (el umbral de envío gratuito y los totales del carrito) proviene de WooCommerce en el mismo sitio, y la hoja de estilo de la barra y el pequeño script de animación se entregan desde la carpeta del complemento, no desde una CDN. Los únicos datos que almacena Nudge son dos opciones de WordPress en su propia base de datos (`nudge_settings` para tu configuración y `nudge_db_version` para actualizaciones), ambas eliminadas cuando elimina el complemento.

== Changelog ==

= 1.0.1 =
* Primera versión estable.

= 0.1.4 =
* Renombrado a Plogins Nudge para WooCommerce para obtener un nombre de complemento más distintivo.

= 0.1.3 =
* Acción `nudge/bar_rendered` y atributo `data-nudge-placement` para balizas de análisis PRO.

= 0.1.2 =
* Filtro `nudge/bar_context` para que PRO pueda ajustar el progreso, los mensajes y los marcadores de niveles.

= 0.1.1 =
* Filtro `nudge/threshold` y `ThresholdResolver::zoneThreshold()` para objetivos PRO por zona.

= 0.1.0 =
* Primera versión: barra de progreso de envío gratuito para el carrito y el pago, con un umbral automático o manual, actualizaciones en vivo a medida que cambia el carrito, mensajes editables, soporte para modo oscuro y movimiento reducido, y una pantalla de configuración en WooCommerce → Nudge.
