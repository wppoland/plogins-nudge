=== Plogins Nudge - Free Shipping Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Una barra de progreso de envío gratis que indica a los compradores de WooCommerce cuánto más deben añadir para conseguir la entrega gratuita.

== Description ==

Nudge muestra a los compradores cuánto les falta en el carrito para alcanzar tu umbral de envío gratis y
cuánto más deben añadir para lograrlo. El mensaje se actualiza a medida que cambia el carrito,
así que «envío gratis a partir de 50 $» deja de ser letra pequeña y se convierte en una cifra
sobre la que el cliente puede actuar.

De forma predeterminada, el umbral viene directamente de WooCommerce. Nudge revisa tus
métodos de envío gratis activados en todas las zonas de envío y usa la
cantidad mínima de pedido más baja que encuentra, así que no mantienes la cifra en dos sitios. Si
prefieres fijarla tú mismo, cambia al modo manual y escribe una cantidad fija; esa
cantidad también se usa como respaldo cuando el modo automático no encuentra ningún método válido.

Cuando no hay nada útil que mostrar (la barra está desactivada, el carrito está vacío o no hay
umbral configurado), Nudge no muestra nada en lugar de una barra vacía o
siempre completada.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-nudge/docs/
* <strong>Página del plugin</strong> - https://plogins.com/es/plogins-nudge/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-nudge
* <strong>Informes de errores y peticiones de funciones</strong> - https://github.com/wppoland/plogins-nudge/issues


= What it does =

* Lee el umbral de envío gratis automáticamente desde tus métodos activos de
  envío gratis de WooCommerce o acepta una cantidad fija que defines a mano.
* Se vuelve a renderizar con el carrito tanto en las páginas clásicas de carrito y pago como en los
  bloques de carrito y pago. Un script pequeño (sin jQuery propio) anima el ancho
  entre actualizaciones.
* Expone un `role="progressbar"` real con `aria-valuenow`/`min`/`max` y un
  mensaje de texto legible para lectores de pantalla; respeta prefers-reduced-motion.
* Reserva la altura de la barra antes de pintar, así que añadirla no desplaza el diseño.
* Estiliza la barra con propiedades CSS personalizadas `--nudge-*` y se adapta a esquemas de color
  oscuros, para que los temas puedan recolorearla sin editar el marcado.
* Te permite escribir los mensajes de progreso y de éxito, con un token `{amount}` en el
  mensaje de progreso para el total restante.
* Incluye un archivo POT, elimina sus opciones al desinstalar y declara compatibilidad con HPOS y
  con los bloques de carrito y pago.

El código fuente y los informes de errores están en GitHub: https://github.com/wppoland/plogins-nudge

== Installation ==

1. Sube el plugin a `/wp-content/plugins/nudge` o instálalo desde Plugins → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Ve a <strong>WooCommerce → Nudge</strong>, activa la barra y elige dónde se muestra.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Nudge no hace nada hasta que WooCommerce esté activo.

= Where does the free-shipping amount come from? =

En el modo automático, Nudge lee la cantidad mínima de pedido de tus métodos activos de
envío gratis de WooCommerce y usa la más baja en todas tus zonas de
envío. En el modo manual fijas tú una cantidad fija. Si el modo automático no encuentra ningún
método con cantidad mínima de pedido, usa en su lugar la cantidad manual.

= What shows when no free-shipping goal is configured? =

Nada. En lugar de mostrar una barra vacía o siempre completa, Nudge omite la salida
por completo hasta que haya un umbral real hacia el que contar.

= Does it work with the Cart and Checkout blocks? =

Sí. Se renderiza en las plantillas clásicas de carrito y pago y en los bloques de
carrito y pago de WooCommerce, y declara compatibilidad con HPOS y con los bloques de carrito y pago.

= Can I change the wording and colours? =

Sí. Los mensajes de progreso y de éxito se pueden editar en la pantalla de ajustes, y
los colores y el tamaño de la barra son propiedades CSS personalizadas `--nudge-*` que tu tema puede
sobrescribir.


= Does this plugin work on WordPress Multisite? =

Sí. Este plugin es compatible con WordPress Multisite. Actívalo para toda la red o en sitios concretos; cada sitio conserva sus propios ajustes y datos.

== Screenshots ==

1. La barra de progreso de envío gratis en el carrito.
2. La pantalla de ajustes de Nudge.

== External Services ==

Nudge no se conecta a ningún servicio externo. No envía analíticas, no registra ninguna licencia, no carga fuentes ni scripts remotos ni realiza ninguna petición HTTP fuera de tu servidor. Todo lo que necesita (tu umbral de envío gratis y los totales del carrito) proviene de WooCommerce en el mismo sitio, y la hoja de estilos de la barra y el pequeño script de animación se sirven desde la carpeta del plugin, no desde una CDN. Los únicos datos que almacena Nudge son dos opciones de WordPress en tu propia base de datos (`nudge_settings` para tu configuración y `nudge_db_version` para actualizaciones), ambas eliminadas cuando borras el plugin.

== Translations ==

Plogins Nudge incluye traducciones al polaco, al alemán y al español para la interfaz del plugin. El dominio de texto es `plogins-nudge`, por lo que los paquetes de idioma de WordPress.org también pueden sustituir o ampliar estas traducciones incluidas.

== Changelog ==

= 1.0.2 =
* Se añadieron traducciones incluidas al polaco, al alemán y al español para la interfaz del plugin.

= 1.0.1 =
* Primera versión estable.

= 0.1.4 =
* Renombrado a Plogins Nudge for WooCommerce para un nombre de plugin más distintivo.

= 0.1.3 =
* Acción `nudge/bar_rendered` y atributo `data-nudge-placement` para balizas de analítica PRO.

= 0.1.2 =
* Filtro `nudge/bar_context` para que PRO pueda ajustar el progreso, los mensajes y los marcadores de niveles.

= 0.1.1 =
* Filtro `nudge/threshold` y `ThresholdResolver::zoneThreshold()` para objetivos PRO por zona.

= 0.1.0 =
* Primer lanzamiento: barra de progreso de envío gratis para el carrito y el pago, con umbral automático o manual, actualizaciones en directo a medida que cambia el carrito, mensajes editables, soporte de modo oscuro y movimiento reducido, y una pantalla de ajustes en WooCommerce → Nudge.
