=== MapeaPost ===
Contributors: dev@glaucuss.com
Donate link: https://glaucussoft.com/mapeapost/
Tags: mapa, marcador, localización, entrada
Requires at least: 3.0.1
Tested up to: 4.9.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin que permite añadir una geolocalización a las entradas del sitio para posteriormente ubicarlas en un mapa.

== Description ==

Este plugin permite que añadas coordenadas geoespaciales a las entradas publicadas en tu blog. Puedes añadir también un label (texto o número) que se recomienda sea de pocos caracteres. A partir de un shortcode el plugin permite mostrar las entradas como marcadores en un mapa. En cada marcador aparecerá el label que se haya indicado (o un punto en caso de dejarse vacio) por ello se recomienda que el label no sea muy extenso para que no sobresalga del marcador. El shortcode para generar el mapa permite filtrar por categorias de entradas, especificar el zoom inicial además del centro del mapa. Al hacer click en un marcador se abrirá un globo que contendrá el título de la entrada y un enlace para dirigirse a ella.
Para más información visite nuestra [página](https://glaucussoft.com/mapeapost/).

== Installation ==

Método de instalación y configuración del plugin

1. Copia el contenido del zip en la ruta `/wp-content/plugins/` de tu sitio.
1. Activa el plugin en la vista de 'Plugins' en WordPress
1. En Ajustes > MapeaPost pega tu API key de GoogleMaps

== Frequently Asked Questions ==

= ¿Cómo puedo conseguir mi API key de GoogleMaps? =

Vaya al siguiente enlace: [https://developers.google.com/maps/documentation/javascript/get-api-key?hl=ES](https://developers.google.com/maps/documentation/javascript/get-api-key?hl=ES)

= ¿Como creo un shortcode de mappost? =

Para crear el shortcode basta con pegar el siguiente código en una página o entrada de su sitio:
[mappost_mapa]
Para crear un shortcode de un mapa sobre las entradas de una categoría utilice el atributo 'categoria' y el nombre (slug) de la categoría. Por ejemplo:
[mappost_mapa categoria="mi-categoria"]
Para crear un shortcode de un mapa centrado en una localización especifique el atributo 'centro' y para el zoom inicial utilize 'zoom'. Por ejemplo:
[mappost_mapa centro="36.123456,-4.567890" zoom="10"]

== Screenshots ==

1. Esta captura de pantalla corresponde al mapa mostrando una entrada sobre el 'Restaurante Casa José María' con una etiqueta
de un 9
1. Esta captura de pantalla corresponde con el panel lateral en la edición de un 'post' para introducir las coordenadas del marcador
1. Esta captura de pantalla corresponde al panel de ajustes. Los números indican el recorrido para llegar hasta el panel.

== Changelog ==

= 1.0.1 =
* Correción de compatibilidad en ajustes

= 1.0 =
* Primera versión liberada

== Upgrade Notice ==

= 1.0.1 =
Actualiza si tienes problemas al mostrar el mapa en tu sitio web
= 1.0 =
Muestra tus post localizados en un mapa
