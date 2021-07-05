# Aplicación Web para Gestión de Datos Genómicos de Cánceres de Pulmón
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8247470da2c84b46af85f18a05d50986)](https://www.codacy.com/gh/CarlosOrtu/TFG_HUBU/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=CarlosOrtu/TFG_HUBU&amp;utm_campaign=Badge_Grade)
## TFG Universidad De Burgos

* Autor: *Carlos Ortúñez Rojo*
* Tutores: *Dr. Jesús Manuel Maudes Raedo* y *Dr. José Francisco Díez Pastor*
---

Web del proyecto: http://tfg-hubu.herokuapp.com/login   
Usuario: administrador@gmail.com  
Contraseña: UniversidadDeBurgos  

---

## Resumen

El presente proyecto trata sobre desarrollo de una aplicación web que permite la introducción, modificación, eliminación y visualización de los datos genómicos de pacientes con cáncer de pulmón del Hospital Universitario de Burgos. Esta aplicación web también cuenta con un sistema de autenticación y un sistema de gestión de usuarios.

Todos los datos están guardados en una base de datos relacional para permitir un rápido acceso a estos y ahorrar espacio a la hora de almacenarlos.

Dentro de la visualización de los datos se han incluido varias opciones para permitir a los oncólogos una mejor explotación a la hora de realizar sus estudios. Estas opciones de visualización incluyen: la exportación a un fichero .xlsx, la representación en diagramas tanto de sectores como de columnas, la obtención de tablas de frecuencias y de percentiles y la visualización individual de los datos crudos de cada paciente.

Para poder comprobar el correcto funcionamiento de toda la parte de visualización nombrada anteriormente, se ha realizado en Python un script para poder generar una base de datos sintética. Permitiendo al usuario elegir diferentes parámetros de las distribuciones que se han considerado más óptimas para el tipo de datos con los que se está trabajando.
