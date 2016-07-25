[33m3509e660922eda879181bc59354b2b43931e99c2[m Merge en app.js resuelto
[33m835b557071d1840a70b3457ef61f7d2783154f30[m Se agrego filtro por fecha y numero de pedido - se optimizo el mismo metodo de filtro de movimientos entre fechas
 app/Http/Controllers/AuxMovementController.php     | 384 [32m+++++++[m[31m--------------[m
 public/app/partials/auxIndicator7.html             |  27 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxIndicator7Ctrl.js |  10 [32m+[m[31m-[m
 resources/views/pdf/templatePDF.blade.php          |   4 [32m+[m[31m-[m
 4 files changed, 152 insertions(+), 273 deletions(-)
[33m20ab261a6b91d90367469ee32351d636d60645c8[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m4a6f1a64e2f497dedb561741645f18501469842a[m configuracion de update
 .editorconfig                                      |  35 [32m++[m
 app/Http/Controllers/AuxSocialController.php       |   1 [32m+[m
 public/app/app.js                                  |   4 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxIndicator7Ctrl.js |   2 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxMovementCtrl.js   | 662 [32m+++++++++++[m[31m----------[m
 .../assets/js/app/controllers/auxProductCtrl.js    |   5 [32m+[m[31m-[m
 6 files changed, 374 insertions(+), 335 deletions(-)
[33m33a9e19e66c4e0dc7680a02b1ba92cafcea9fec3[m Se observo la hoja de depacho por fecha
 app/Http/Controllers/AuxMovementController.php | 12 [32m+++[m[31m-----[m
 public/app/app.js                              | 40 [32m+++++++++++++[m[31m-------------[m
 public/app/partials/auxMovement2.html          |  2 [32m+[m[31m-[m
 3 files changed, 25 insertions(+), 29 deletions(-)
[33m0b0754b27cf255dba4d91f42e5573c1d686019d7[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m2de1fc731f66435c0dce9cd9561e1fcc96aa7623[m Parche para despachi -.por revisar
 app/Http/Controllers/AuxMovementController.php | 35 [32m+++++++++++++++[m[31m-----------[m
 1 file changed, 21 insertions(+), 14 deletions(-)
[33m7afebe23c550363830ebcf4cb5aa7ff5c980b3f0[m se corrigio diseño de confirmación
 resources/assets/js/app/controllers/auxMovementCtrl.js | 6 [32m+[m[31m-----[m
 1 file changed, 1 insertion(+), 5 deletions(-)
[33mb13970b01f76d2f4e7a403a89418d583cbc7aacd[m merge
[33m7ee8673847ca8abe6a423d3f97ebbff6744a2b4f[m Ae agrego vista y descarga de despacho por fecha - pruebas
 app/Http/Controllers/AuxMovementController.php     | 109 [32m+++++++++++++++++++++[m
 .../Controllers/auxProductFiltersController.php    |   2 [32m+[m[31m-[m
 app/Http/routes.php                                |   2 [32m+[m
 public/app/partials/auxMovement2.html              |  21 [32m+++[m[31m-[m
 .../assets/js/app/controllers/auxIndicator7Ctrl.js |   4 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxMovement2Ctrl.js  |  42 [32m+++++++[m[31m-[m
 .../js/app/controllers/auxMovement2_JVECtrl.js     |  51 [32m++++++++[m[31m--[m
 .../assets/js/app/controllers/auxProductJVECtrl.js |   2 [32m+[m[31m-[m
 8 files changed, 221 insertions(+), 12 deletions(-)
[33m2b98aa2a9b20ab7199497943be7654d06c050948[m se corrigio label de confirmacion de registro de movimientos
 resources/assets/js/app/controllers/auxMovementCtrl.js | 4 [32m++[m[31m--[m
 1 file changed, 2 insertions(+), 2 deletions(-)
[33mcaf0bcdc1126b18f7cab7632316e2238e9a82123[m Se agrego confirmacion de fecha de envio en salida y reprogramacion
 app/Http/Controllers/AuxMovementController.php     | 101 [32m++[m[31m-------------------[m
 public/app/app.js                                  |  10 [32m++[m
 .../assets/js/app/controllers/auxMovement2Ctrl.js  |   9 [32m+[m[31m-[m
 .../js/app/controllers/auxMovement2_JVECtrl.js     |  12 [32m+[m[31m--[m
 .../assets/js/app/controllers/auxMovementCtrl.js   |  20 [32m+++[m[31m-[m
 5 files changed, 37 insertions(+), 115 deletions(-)
[33m1445f708187c8e58461fa9f662a6712e9ccb60ee[m ed
[33m9c2681b10f7819c0e2103d51fbbba3bb060dca5f[m Correccion de informacion de registro de salida
 app/Http/Controllers/AuxMovementController.php     | 355 [32m+++++++++++[m[31m----------[m
 .../assets/js/app/controllers/auxMovementCtrl.js   |   3 [32m+[m
 sublime-gulp.log                                   |  19 [32m++[m
 3 files changed, 205 insertions(+), 172 deletions(-)
[33m04a81349a3e3381e9267a2fa1d50d98fa071ab9f[m Se arreglo uso de promesa en app metodo service
 public/app/app.js                                  | 49 [32m++++++++++++[m[31m----------[m
 .../assets/js/app/controllers/auxProductCtrl.js    | 24 [32m++++[m[31m-------[m
 2 files changed, 35 insertions(+), 38 deletions(-)
[33m407ba161b03964a0f8fafe87ce0347331b7a31d5[m Se agrego filtro de busqueda de stock a JVE
 app/Http/Controllers/AuxProductController.php      |   4 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxStockCtrl.js      | 132 [32m+++++++++++++++++++++[m
 .../assets/js/app/controllers/auxStockVENCtrl.js   |   6 [32m+[m[31m-[m
 3 files changed, 137 insertions(+), 5 deletions(-)
[33m0c64603b5fa20c6d22a1804fe2407b29cd0eefd9[m Se rediuseño metodo de busqueda avanzado de producto - se agrego columnas de color y talla en confirmacion de salida en generar salidas
 app/Http/Controllers/AuxMovementController.php     | 20 [32m+++++[m[31m--[m
 .../Controllers/auxProductFiltersController.php    | 24 [32m++[m[31m-------[m
 public/app/partials/auxProduct.html                |  4 [32m+[m[31m-[m
 public/app/partials/auxProductJVE.html             |  4 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxMovementCtrl.js   | 61 [32m+++++++++[m[31m-------------[m
 .../assets/js/app/controllers/auxProductCtrl.js    | 27 [32m++[m[31m--------[m
 .../assets/js/app/controllers/auxProductJVECtrl.js | 27 [32m++[m[31m--------[m
 .../assets/js/app/controllers/auxStockVENCtrl.js   | 31 [32m+++[m[31m--------[m
 resources/views/pdf/templatePDF.blade.php          |  1 [32m+[m
 9 files changed, 68 insertions(+), 131 deletions(-)
[33ma10b39a86c25858f8da60c119953e35c5bfc59a5[m Se arreglo bug en pdf de reporte de movimientos
 app/Http/Controllers/AuxMovementController.php | 15 [32m++++++++++++++[m[31m-[m
 resources/views/pdf/templatePDF.blade.php      |  8 [32m++++[m[31m----[m
 2 files changed, 18 insertions(+), 5 deletions(-)
[33m550f21d07d4ed709e5832f6fff66fae1e915b744[m Se agrego columnas de creacion de movimiento y codigo de pedido
 app/Http/Controllers/AuxMovementController.php     | 40 [32m+++++[m[31m-----------------[m
 .../Controllers/auxProductFiltersController.php    |  1 [31m-[m
 .../assets/js/app/controllers/auxIndicator7Ctrl.js |  5 [32m+[m[31m--[m
 3 files changed, 11 insertions(+), 35 deletions(-)
[33mc9139d3ad6e8f99089f5c9d1232a90bdeff1fccc[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m040aef0783c61f584f3a826aba616c99a0b63912[m Se agrego filtro de busqueda a vendedor(a)
 .../Controllers/auxProductFiltersController.php    | 153 [32m+++++++++++++++[m[31m------[m
 app/Http/routes.php                                |   1 [32m+[m
 .../assets/js/app/controllers/auxProductCtrl.js    |   3 [31m-[m
 .../assets/js/app/controllers/auxProductJVECtrl.js |   5 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxStockVENCtrl.js   | 149 [32m++++++++++++++++++++[m
 5 files changed, 262 insertions(+), 49 deletions(-)
[33m394a4bdcf1898ec8c5a52eb6a5b37c1696e878c9[m Se agrego correcion de opciones en la vista de confirmación
 resources/assets/js/app/controllers/auxMovement2Ctrl.js     | 3 [32m+++[m
 resources/assets/js/app/controllers/auxMovement2_JVECtrl.js | 3 [32m+++[m
 2 files changed, 6 insertions(+)
[33md07c368723a25540a10bc4c954f330fd8c4e8c24[m Se agrego nueva opcion de retorno en todas las vistas utilizadas y controllador de API REST
 app/Http/Controllers/AuxMovementController.php                | 3 [32m++[m[31m-[m
 resources/assets/js/app/controllers/auxMovement2Ctrl.js       | 3 [32m++[m[31m-[m
 resources/assets/js/app/controllers/auxMovement2_JVECtrl.js   | 3 [32m++[m[31m-[m
 resources/assets/js/app/controllers/auxMovementOutFit2Ctrl.js | 3 [32m++[m[31m-[m
 4 files changed, 8 insertions(+), 4 deletions(-)
[33m152f1ecd650d5292ad0bdfc4995e26a0e66c7e33[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m5c09b3bf1332ad687bd632ee7b1ae41b7daac303[m Se corrigio la dimension de los input de creacion de movimiento
 public/app/partials/auxMovement.html | 18 [32m+++++++++[m[31m---------[m
 1 file changed, 9 insertions(+), 9 deletions(-)
[33m0ba6b3b3199657963c93eb13df8df8646f506ad6[m Se termino filtro de busqueda avanzado por productos - god|adm|jve
 .../Controllers/auxProductFiltersController.php    |  76 [32m+++++++[m[31m--[m
 public/app/partials/auxProduct.html                |  60 [32m++++++[m[31m--[m
 public/app/partials/auxProductJVE.html             |  80 [32m+++++[m[31m-----[m
 .../assets/js/app/controllers/auxProductCtrl.js    | 171 [32m++++++++++++++++++[m[31m---[m
 .../assets/js/app/controllers/auxProductJVECtrl.js |  62 [32m++++++[m[31m--[m
 5 files changed, 349 insertions(+), 100 deletions(-)
[33me0fbc89e0e5078175b7c764b4fd47e8ac2d05c8c[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mda9a6619e973f688996eaeb03c0085cba7ae293b[m Se agrego filtro de busqueda - por terminar
 .../Controllers/auxProductFiltersController.php    | 122 [32m+++++++++++++++++++++[m
 app/Http/routes.php                                |  18 [32m+[m[31m--[m
 gulpfile.js                                        |  10 [32m+[m[31m-[m
 public/app/partials/auxProductJVE.html             |  47 [32m++++++++[m
 .../assets/js/app/controllers/auxProductCtrl.js    |   3 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxProductJVECtrl.js |  79 [32m++++++++++++[m[31m-[m
 6 files changed, 261 insertions(+), 18 deletions(-)
[33maa52bbac0d9631194e371caf66da5d5d9afcede3[m Se corrigio fecha de pedido
 public/app/partials/auxMovement.html                        | 2 [32m+[m[31m-[m
 resources/assets/js/app/controllers/auxMovement2Ctrl.js     | 2 [32m+[m[31m-[m
 resources/assets/js/app/controllers/auxMovement2_JVECtrl.js | 2 [32m+[m[31m-[m
 3 files changed, 3 insertions(+), 3 deletions(-)
[33mccf663e1d01ad6a08b438c6212d33543e668ab2e[m Se corrigio texto de codigo de pedido
 public/app/partials/auxMovement.html                        | 2 [32m+[m[31m-[m
 resources/assets/js/app/controllers/auxMovement2Ctrl.js     | 2 [32m+[m[31m-[m
 resources/assets/js/app/controllers/auxMovement2_JVECtrl.js | 2 [32m+[m[31m-[m
 3 files changed, 3 insertions(+), 3 deletions(-)
[33mffa463419ba94b2196ab2c233132e41587a43905[m Se agrego la opcion de enviar productos solo con una fecha de salida
 app/Http/Controllers/AuxMovementController.php     |  5 [32m+[m[31m--[m
 public/app/partials/auxMovement.html               | 23 [32m+++++++++[m[31m----[m
 .../assets/js/app/controllers/auxMovementCtrl.js   | 38 [32m+++++++++++++[m[31m---------[m
 3 files changed, 41 insertions(+), 25 deletions(-)
[33mc6431569ba462372aa0d004be1e316b3bb9ff2b3[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m03ca187389f79f70af5e09e524b8a53a50618c44[m Correccion de actualizcion
 _ide_helper.php | 24 [32m++++++++++++[m[31m------------[m
 composer.lock   | 12 [32m++++++[m[31m------[m
 2 files changed, 18 insertions(+), 18 deletions(-)
[33m7afc03aebac95f5feb27b523ccbabae8815cb8f4[m Se agrego fecha y codigo de orden de pedido y se actualizado algunas vistas loading
 app/Http/Controllers/AuxMovementController.php     |  6 [32m++[m[31m--[m
 ..._07_19_154636_update_table_auxmovement_add2.php | 32 [32m++++++++++++++++++++++[m
 public/app/partials/auxMovement.html               | 15 [32m++++++++[m[31m--[m
 .../assets/js/app/controllers/auxMovement2Ctrl.js  |  4 [32m++[m[31m-[m
 .../js/app/controllers/auxMovement2_JVECtrl.js     | 10 [32m++++[m[31m---[m
 .../assets/js/app/controllers/auxMovementCtrl.js   |  7 [32m+++[m[31m--[m
 .../assets/js/app/controllers/auxStockCtrl.js      |  5 [32m+++[m[31m-[m
 7 files changed, 66 insertions(+), 13 deletions(-)
[33m3b24d1bd31cf41d4e9b866792bcd4960d334984d[m Se agrego versionaje a estilos
 gulpfile.js                                |  18 [32m+[m[31m-[m
 public/css/app.min.1.css                   |   4 [32m+[m[31m-[m
 resources/assets/sass/app1.css             | 376 [32m++++++++++++++[m[31m---------------[m
 resources/views/auxGod.blade.php           |  83 [32m++++[m[31m---[m
 resources/views/layouts/dashHead.blade.php |   6 [32m+[m[31m-[m
 5 files changed, 249 insertions(+), 238 deletions(-)
[33m163dff7bfe516a473e84cbc70e622da001aaeb54[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mafe9c0df15f8c8e516386d4f2727bb5ecfbd46be[m Se agrego migracion y fecha de movimiento
 app/Http/Controllers/AuxMovementController.php     |  5 [32m+++[m[31m-[m
 app/Http/Controllers/AuxProductController.php      |  5 [32m+++[m[31m-[m
 ...6_07_19_123103_update_table_auxmovement_add.php | 32 [32m++++++++++++++++++++++[m
 public/app/partials/auxMovement.html               | 17 [32m+++++++++++[m[31m-[m
 4 files changed, 56 insertions(+), 3 deletions(-)
[33m6524bfd07a69872a2d4c9f2e838c2a6bb38a7542[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m460b09295e76fc8a7c4c6b1e8fda6aa50e035ad1[m Se corrgio bugg con cpdigos en salida de productos - y las vista de confirmacion de productos a salir cuando son muchos
 app/Http/Controllers/AuxMovementController.php     |     1 [32m+[m
 gulpfile.js                                        |     6 [32m+[m[31m-[m
 public/app/partials/auxProductJVE.html             |     5 [32m+[m[31m-[m
 public/css/app.min.1.css                           | 12577 [32m+[m[31m------------------[m
 public/css/app.min.2.css                           |  1184 [32m+[m[31m-[m
 public/css/loginStyles.min.css                     |    14 [32m+[m[31m-[m
 public/css/styles.min.css                          |     7 [32m+[m[31m-[m
 public/css/toastr.min.css                          |   198 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxMovementCtrl.js   |    86 [32m+[m[31m-[m
 resources/assets/sass/app2.css                     |     7 [32m+[m
 10 files changed, 71 insertions(+), 14014 deletions(-)
[33m810195a3d4233ce28a38fade59326cbb58369eb3[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m5e1cd7185cfb3c15baf2f4fa0efde5a16663f1ab[m Se actualizo composer
 _ide_helper.php                               |  2 [32m+[m[31m-[m
 app/Http/Controllers/AuxProductController.php |  2 [32m+[m[31m-[m
 composer.lock                                 | 12 [32m++++++[m[31m------[m
 3 files changed, 8 insertions(+), 8 deletions(-)
[33m5a61c311cfdac3bebaaef208639eb536426d9870[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m10261aa1c70b8e26cf5cd13f7f070044d479f4bc[m Se corrigio bug de vista de stock de productos
 app/Http/Controllers/AuxProductController.php | 2 [32m+[m[31m-[m
 gulpfile.js                                   | 2 [32m+[m[31m-[m
 resources/views/vendedor.blade.php            | 2 [32m+[m[31m-[m
 3 files changed, 3 insertions(+), 3 deletions(-)
[33m15003e4a53be135c6c34e254362c75d01fe119f0[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m41beb703a1815b4e0cc21bedbeb26f20f391f564[m repocision de vista de kardex para ventas - revision de stock de productos pendiente
 gulpfile.js                                              | 2 [32m+[m[31m-[m
 resources/assets/js/app/controllers/auxProductVENCtrl.js | 2 [32m+[m[31m-[m
 resources/views/vendedor.blade.php                       | 4 [32m++[m[31m--[m
 3 files changed, 4 insertions(+), 4 deletions(-)
[33m261589f5cc753d687f7cdb92f948881dd3367336[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33maa78d44243e21200d8a876ee42aa14cb98097d53[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mbae911ef6301fd6c6e5064246fcfb481fd160ff4[m Se actualizo stock de productos y se le agrego la vista a ventas, se removio vista de ja}kardex a ventas
 app/Http/Controllers/AuxProductController.php      | 22 [32m+++++[m[31m---[m
 gulpfile.js                                        |  2 [32m+[m[31m-[m
 .../assets/js/app/controllers/auxProductJVECtrl.js | 14 [31m------[m
 .../assets/js/app/controllers/auxStockCtrl.js      | 37 [32m++++[m[31m----------[m
 .../assets/js/app/controllers/auxStockVENCtrl.js   | 58 [32m++++++++++++++++++++++[m
 resources/views/vendedor.blade.php                 |  4 [32m+[m[31m-[m
 6 files changed, 84 insertions(+), 53 deletions(-)
[33m887ecfd2e3072be378de1b7f2f2d5089205a9bb4[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33ma3374512be53f571c9da84ce55e761ed7c269889[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m66208d1421a40d92a655fe8adb0449cfb29bba06[m Se agrego carga de vista de ventas
 public/app/app.js                               |  3 [32m++[m
 public/app/controllers/PartnerPanelCtrl.js      | 16 [32m+++[m[31m----[m
 public/app/controllers/auxIndicator1Ctrl.js     |  6 [32m++[m[31m-[m
 public/app/controllers/auxMovementCtrl.js       |  1 [31m-[m
 public/app/controllers/auxMovementDayCtrl.js    |  1 [31m-[m
 public/app/controllers/auxMovementOutFitCtrl.js |  1 [31m-[m
 public/app/controllers/auxProductCtrl.js        |  2 [32m+[m[31m-[m
 public/app/controllers/auxProductJVECtrl.js     |  1 [31m-[m
 public/app/controllers/auxStockCtrl.js          |  6 [32m++[m[31m-[m
 public/app/controllers/liquidationCtrl.js       |  1 [31m-[m
 public/app/controllers/outFitCtrl.js            |  1 [31m-[m
 public/app/partials/PartnerPanel.html           | 62 [32m+++++++++++++++++++++++++[m
 public/app/partials/auxMovement.html            |  9 [32m+++[m[31m-[m
 public/app/partials/auxMovement2.html           |  5 [32m+[m[31m-[m
 public/app/partials/auxMovementDay.html         |  5 [32m+[m[31m-[m
 public/app/partials/auxMovementOutFit.html      |  5 [32m+[m[31m-[m
 public/app/partials/auxMovementOutFit2.html     |  5 [32m+[m[31m-[m
 public/app/partials/auxProduct.html             |  5 [32m+[m[31m-[m
 public/app/partials/auxProductJVE.html          |  5 [32m+[m[31m-[m
 public/app/partials/liquidation.html            |  5 [32m+[m[31m-[m
 public/app/partials/outFit.html                 |  5 [32m+[m[31m-[m
 resources/views/layouts/app.blade.php           |  2 [32m+[m
 resources/views/layouts/dashHead.blade.php      |  4 [32m+[m[31m-[m
 23 files changed, 126 insertions(+), 30 deletions(-)
[33m0699ad4e090225478dc576793227054f86aff068[m Se agrego columna de estado de venta del producto normal-liquidacion
 gulpfile.js                                        |  8 [32m+[m[31m--[m
 public/.gitignore                                  |  2 [32m+[m[31m-[m
 public/app/.gitignore                              |  2 [32m+[m[31m-[m
 .../controllers/compile/coordinadorControllers.js  |  2 [31m-[m
 .../compile/coordinadorControllers.js.map          |  1 [31m-[m
 public/app/controllers/compile/godControllers.js   |  5 [31m--[m
 .../app/controllers/compile/godControllers.js.map  |  1 [31m-[m
 .../app/controllers/compile/vendedorControllers.js |  1 [31m-[m
 .../controllers/compile/vendedorControllers.js.map |  1 [31m-[m
 .../Compile/coordinadorControllers-6365438af3.js   |  2 [31m-[m
 .../Compile/coordinadorControllers.js.map          |  1 [31m-[m
 .../Compile/godControllers-ac055e5527.js           |  5 [31m--[m
 .../app/controllers/Compile/godControllers.js.map  |  1 [31m-[m
 .../Compile/vendedorControllers-cefa4a2efe.js      |  1 [31m-[m
 .../controllers/Compile/vendedorControllers.js.map |  1 [31m-[m
 public/build/rev-manifest.json                     |  5 [31m--[m
 .../assets/js/app/controllers/auxProductJVECtrl.js | 61 [32m++++++++++++++++[m[31m------[m
 17 files changed, 51 insertions(+), 49 deletions(-)
[33m8c60c023776247e803beb2215af33bdf57d8450b[m git remove cache gitignore
 public/.gitignore                  |   1 [32m+[m
 public/img/publicities/default.jpg | Bin [31m651570[m -> [32m0[m bytes
 2 files changed, 1 insertion(+)
[33m3b69f78c7a95daee343e7b586f7528e98cd94e00[m Sea grego gitignore para carpeta app de angular
 public/app/.gitignore | 1 [32m+[m
 1 file changed, 1 insertion(+)
[33m9b1f7200dd669d1855dda909ed9e88b273960bd7[m Se configuro control de vewrsiones, union y minificacion de controladores de usuarios del sistema
 gulpfile.js                                        | 119 [32m+++++[m[31m-[m
 package.json                                       |   8 [32m+[m[31m-[m
 public/app/controllers/PartnerCtrl.js              | 336 [31m-----------------[m
 public/app/controllers/PartnerPanelCtrl.js         | 255 [31m-------------[m
 public/app/controllers/PaymentCtrl.js              | 218 [31m-----------[m
 public/app/controllers/PaymentListCtrl.js          | 233 [31m------------[m
 public/app/controllers/addressesCtrl.js            | 162 [31m--------[m
 public/app/controllers/auxAlarmCtrl.js             |  52 [31m---[m
 public/app/controllers/auxEsquemaCtrl.js           |  83 [31m-----[m
 public/app/controllers/auxIndicator1Ctrl.js        |  67 [31m----[m
 public/app/controllers/auxIndicator2Ctrl.js        |  66 [31m----[m
 public/app/controllers/auxIndicator3Ctrl.js        |  66 [31m----[m
 public/app/controllers/auxIndicator4Ctrl.js        |  64 [31m----[m
 public/app/controllers/auxIndicator5Ctrl.js        |  55 [31m---[m
 public/app/controllers/auxIndicator6Ctrl.js        |  53 [31m---[m
 public/app/controllers/auxIndicator7Ctrl.js        | 174 [31m---------[m
 public/app/controllers/auxMovement2Ctrl.js         | 315 [31m----------------[m
 public/app/controllers/auxMovement2_JVECtrl.js     | 274 [31m--------------[m
 public/app/controllers/auxMovementCtrl.js          | 310 [31m----------------[m
 public/app/controllers/auxMovementDayCtrl.js       |  86 [31m-----[m
 public/app/controllers/auxMovementOutFit2Ctrl.js   | 188 [31m----------[m
 public/app/controllers/auxMovementOutFitCtrl.js    | 234 [31m------------[m
 public/app/controllers/auxProductCtrl.js           | 407 [31m--------------------[m
 public/app/controllers/auxProductJVECtrl.js        | 141 [31m-------[m
 public/app/controllers/auxProductVENCtrl.js        |  62 [31m----[m
 public/app/controllers/auxProviderProductCtrl.js   | 227 [31m------------[m
 public/app/controllers/auxStockCtrl.js             | 129 [31m-------[m
 public/app/controllers/commentsCtrl.js             | 139 [31m-------[m
 .../controllers/compile/coordinadorControllers.js  |   2 [32m+[m
 .../compile/coordinadorControllers.js.map          |   1 [32m+[m
 public/app/controllers/compile/godControllers.js   |   5 [32m+[m
 .../app/controllers/compile/godControllers.js.map  |   1 [32m+[m
 .../app/controllers/compile/vendedorControllers.js |   1 [32m+[m
 .../controllers/compile/vendedorControllers.js.map |   1 [32m+[m
 public/app/controllers/customersCtrl.js            | 166 [31m---------[m
 public/app/controllers/employeeAssistsCtrl.js      |  50 [31m---[m
 public/app/controllers/employeesCtrl.js            | 324 [31m----------------[m
 public/app/controllers/facebookPublicityCtrl.js    | 107 [31m------[m
 public/app/controllers/godEmployeeAssistsCtrl.js   |  51 [31m---[m
 public/app/controllers/indicatorPayRoleCtrl.js     | 107 [31m------[m
 public/app/controllers/interestsCtrl.js            | 224 [31m-----------[m
 public/app/controllers/kardex.js                   |  82 [31m-----[m
 public/app/controllers/liquidationCtrl.js          | 161 [31m--------[m
 public/app/controllers/ordersCtrl.js               | 223 [31m-----------[m
 public/app/controllers/outFitCtrl.js               | 253 [31m-------------[m
 public/app/controllers/payrollEntryCtrl.js         | 133 [31m-------[m
 public/app/controllers/phonesCtrl.js               | 119 [31m------[m
 public/app/controllers/productsCtrl.js             | 347 [31m------------------[m
 public/app/controllers/products_out.js             |  87 [31m-----[m
 public/app/controllers/publicityCtrl.js            | 237 [31m------------[m
 public/app/controllers/publicityJVECtrl.js         | 256 [31m-------------[m
 public/app/controllers/q_AnswerIndicator.js        | 137 [31m-------[m
 public/app/controllers/q_categoriesCtrl.js         | 111 [31m------[m
 public/app/controllers/q_customersCtrl.js          | 246 [31m-------------[m
 public/app/controllers/q_productsCtrl.js           | 188 [31m----------[m
 public/app/controllers/q_questionnairesCtrl.js     | 183 [31m---------[m
 public/app/controllers/q_questionsCtrl.js          | 133 [31m-------[m
 public/app/controllers/scopesCtrl.js               | 202 [31m----------[m
 public/app/controllers/socialsCtrl.js              | 119 [31m------[m
 public/app/controllers/usersCtrl.js                | 182 [31m---------[m
 .../Compile/coordinadorControllers-6365438af3.js   |   2 [32m+[m
 .../Compile/coordinadorControllers.js.map          |   1 [32m+[m
 .../Compile/godControllers-ac055e5527.js           |   5 [32m+[m
 .../app/controllers/Compile/godControllers.js.map  |   1 [32m+[m
 .../Compile/vendedorControllers-cefa4a2efe.js      |   1 [32m+[m
 .../controllers/Compile/vendedorControllers.js.map |   1 [32m+[m
 public/build/rev-manifest.json                     |   5 [32m+[m
 resources/assets/js/app/app.js                     | 377 [32m+++++++++++++++++++[m
 resources/assets/js/app/appLogin.js                |  16 [32m+[m
 resources/assets/js/app/controllers/PartnerCtrl.js | 337 [32m+++++++++++++++++[m
 .../assets/js/app/controllers/PartnerPanelCtrl.js  | 256 [32m+++++++++++++[m
 resources/assets/js/app/controllers/PaymentCtrl.js | 219 [32m+++++++++++[m
 .../assets/js/app/controllers/PaymentListCtrl.js   | 234 [32m++++++++++++[m
 .../assets/js/app/controllers/addressesCtrl.js     | 162 [32m++++++++[m
 .../assets/js/app/controllers/auxAlarmCtrl.js      |  53 [32m+++[m
 .../assets/js/app/controllers/auxEsquemaCtrl.js    |  84 [32m+++++[m
 .../assets/js/app/controllers/auxIndicator1Ctrl.js |  68 [32m++++[m
 .../assets/js/app/controllers/auxIndicator2Ctrl.js |  67 [32m++++[m
 .../assets/js/app/controllers/auxIndicator3Ctrl.js |  67 [32m++++[m
 .../assets/js/app/controllers/auxIndicator4Ctrl.js |  65 [32m++++[m
 .../assets/js/app/controllers/auxIndicator5Ctrl.js |  56 [32m+++[m
 .../assets/js/app/controllers/auxIndicator6Ctrl.js |  54 [32m+++[m
 .../assets/js/app/controllers/auxIndicator7Ctrl.js | 175 [32m+++++++++[m
 .../assets/js/app/controllers/auxMovement2Ctrl.js  | 316 [32m++++++++++++++++[m
 .../js/app/controllers/auxMovement2_JVECtrl.js     | 275 [32m++++++++++++++[m
 .../assets/js/app/controllers/auxMovementCtrl.js   | 311 [32m++++++++++++++++[m
 .../js/app/controllers/auxMovementDayCtrl.js       |  87 [32m+++++[m
 .../js/app/controllers/auxMovementOutFit2Ctrl.js   | 189 [32m++++++++++[m
 .../js/app/controllers/auxMovementOutFitCtrl.js    | 235 [32m++++++++++++[m
 .../assets/js/app/controllers/auxProductCtrl.js    | 408 [32m+++++++++++++++++++++[m
 .../assets/js/app/controllers/auxProductJVECtrl.js | 142 [32m+++++++[m
 .../assets/js/app/controllers/auxProductVENCtrl.js |  63 [32m++++[m
 .../js/app/controllers/auxProviderProductCtrl.js   | 227 [32m++++++++++++[m
 .../assets/js/app/controllers/auxStockCtrl.js      | 130 [32m+++++++[m
 .../assets/js/app/controllers/commentsCtrl.js      | 140 [32m+++++++[m
 .../assets/js/app/controllers/customersCtrl.js     | 166 [32m+++++++++[m
 .../js/app/controllers/employeeAssistsCtrl.js      |  50 [32m+++[m
 .../assets/js/app/controllers/employeesCtrl.js     | 325 [32m++++++++++++++++[m
 .../js/app/controllers/facebookPublicityCtrl.js    | 108 [32m++++++[m
 .../js/app/controllers/godEmployeeAssistsCtrl.js   |  52 [32m+++[m
 resources/assets/js/app/controllers/homeCtrl.js    | 342 [32m+++++++++++++++++[m
 .../js/app/controllers/indicatorPayRoleCtrl.js     | 108 [32m++++++[m
 .../assets/js/app/controllers/interestsCtrl.js     | 224 [32m+++++++++++[m
 resources/assets/js/app/controllers/kardex.js      |  82 [32m+++++[m
 .../assets/js/app/controllers/liquidationCtrl.js   | 162 [32m++++++++[m
 resources/assets/js/app/controllers/loginCtrl.js   |  46 [32m+++[m
 resources/assets/js/app/controllers/ordersCtrl.js  | 223 [32m+++++++++++[m
 resources/assets/js/app/controllers/outFitCtrl.js  | 254 [32m+++++++++++++[m
 .../assets/js/app/controllers/payrollEntryCtrl.js  | 134 [32m+++++++[m
 resources/assets/js/app/controllers/phonesCtrl.js  | 119 [32m++++++[m
 .../assets/js/app/controllers/productsCtrl.js      | 347 [32m++++++++++++++++++[m
 .../assets/js/app/controllers/products_out.js      |  88 [32m+++++[m
 .../assets/js/app/controllers/publicityCtrl.js     | 238 [32m++++++++++++[m
 .../assets/js/app/controllers/publicityJVECtrl.js  | 257 [32m+++++++++++++[m
 .../assets/js/app/controllers/q_AnswerIndicator.js | 138 [32m+++++++[m
 .../assets/js/app/controllers/q_categoriesCtrl.js  | 112 [32m++++++[m
 .../assets/js/app/controllers/q_customersCtrl.js   | 247 [32m+++++++++++++[m
 .../assets/js/app/controllers/q_productsCtrl.js    | 189 [32m++++++++++[m
 .../js/app/controllers/q_questionnairesCtrl.js     | 184 [32m++++++++++[m
 .../assets/js/app/controllers/q_questionsCtrl.js   | 134 [32m+++++++[m
 resources/assets/js/app/controllers/scopesCtrl.js  | 202 [32m++++++++++[m
 resources/assets/js/app/controllers/socialsCtrl.js | 119 [32m++++++[m
 resources/assets/js/app/controllers/usersCtrl.js   | 183 [32m+++++++++[m
 resources/views/auxCoordinador.blade.php           |  13 [32m+[m[31m-[m
 resources/views/auxGod.blade.php                   |  48 [32m+[m[31m--[m
 resources/views/layouts/dashboard.blade.php        |   1 [32m+[m
 resources/views/vendedor.blade.php                 |   4 [32m+[m[31m-[m
 127 files changed, 9797 insertions(+), 8893 deletions(-)
[33m27f9ce2afedfda227b0c11bc972a0b218739a45b[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mcee86145607ba43a31845c53212b75e152e26bd5[m Se agrego estado de reservado a vendedor
 public/app/controllers/auxProductVENCtrl.js | 3 [32m++[m[31m-[m
 1 file changed, 2 insertions(+), 1 deletion(-)
[33m7ccc20eff0bee3b70c59e329f0aad0594eb03c65[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m90e03ef321fc34fae02ca412fb8f32faa00742e8[m Se descomento botones de accion en kardex JVE
 public/app/controllers/auxProductJVECtrl.js | 28 [32m++++++++++++++[m[31m--------------[m
 1 file changed, 14 insertions(+), 14 deletions(-)
[33m1a8c704fabc178fc2ada14021eae088425c1cbaf[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m8b2a9a164843c0c7826372eb14579390dcb09faa[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m102604c7fac96347f26da12064aa5cbccc456899[m Se agrego tabla de resumen de stock
 app/Http/Controllers/AuxProductController.php | 15 [32m++++++++++[m[31m-[m
 public/app/controllers/auxStockCtrl.js        | 37 [32m++++++++++++++++++++++++[m[31m---[m
 2 files changed, 47 insertions(+), 5 deletions(-)
[33m73a2c4d11a8fc037e8d54e5432d843a1a5af07da[m Se agrego opcion de reserva * queda testear y agregar estados en columnas con AJQtable2[D
 app/Http/Controllers/AuxProductController.php | 507 [32m++++++++++++++[m[31m------------[m
 app/Http/routes.php                           |   1 [32m+[m
 public/app/controllers/auxProductJVECtrl.js   |  51 [32m++[m[31m-[m
 3 files changed, 305 insertions(+), 254 deletions(-)
[33m42f7fabff9159366860af256f4512c42fe88ebca[m Se agrego movimientos por producto - se agrego filtro por stado de producto en jqdatatable en kardex
 app/Http/Controllers/AuxProductController.php |  2 [32m+[m[31m-[m
 public/app/controllers/auxProductCtrl.js      | 30 [32m+++++++++++[m[31m--[m
 public/app/controllers/auxProductJVECtrl.js   | 61 [32m+++++++++++++++++++++++++[m[31m-[m
 public/app/controllers/auxProductVENCtrl.js   | 61 [32m++++++++++++++++++++++++++[m
 public/app/partials/auxProduct.html           |  6 [32m+[m[31m--[m
 public/app/partials/auxProductJVE.html        | 62 [32m+++++++++++++++++++++++++++[m
 public/app/partials/auxProductVEN.html        | 13 [32m++++++[m
 resources/views/vendedor.blade.php            |  4 [32m+[m[31m-[m
 8 files changed, 228 insertions(+), 11 deletions(-)
[33m55555cb3e009555041e2346d87c90ee06487fab0[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m3ce2b2937c57aa37e5cfa2e73841f3800d6217e1[m S agrego movimientos por producto en kardex *por terminar - se arreglo bug en filtro de movimientos en reporte de movimientos
 app/Http/Controllers/AuxMovementController.php | 26 [32m+[m[31m----------[m
 app/Http/Controllers/AuxProductController.php  | 36 [32m++++++++++[m[31m-----[m
 app/Http/routes.php                            |  2 [32m+[m
 public/app/controllers/auxProductCtrl.js       | 34 [32m+++++++++++++[m[31m-[m
 public/app/partials/auxProduct.html            | 62 [32m++++++++++++++++++++++++++[m
 5 files changed, 124 insertions(+), 36 deletions(-)
[33mb229578c86030ce7bd4c71ba45e4b19a49866adf[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33ma77048842d4a6384531a17e59390dc77ade72d20[m Correcion de etiqueta
 public/app/partials/auxMovement.html | 2 [32m+[m[31m-[m
 1 file changed, 1 insertion(+), 1 deletion(-)
[33m58b45f1dff4c323625c8e49622e76c821add8470[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m38f81477ff6ecde892fd939fb62a954008117203[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mb10fdd085f587ebf0b987aea91d23ab6b76fa918[m Se agrego cambio columna en reporte de movimientos de strin a button
 public/app/controllers/auxIndicator7Ctrl.js | 9 [32m++++++++[m[31m-[m
 1 file changed, 8 insertions(+), 1 deletion(-)
[33m6aa2e23db863f6498dc12400eb1c0d828779295a[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m8d04549f2b2c4cd37104f317c082a68a6d99b23c[m Se cambio etiqueta en reporte de movimientos
 public/app/controllers/auxIndicator7Ctrl.js | 2 [32m+[m[31m-[m
 1 file changed, 1 insertion(+), 1 deletion(-)
[33m4c737eeddd69b6c2740d8fadb64ee77c0241c15f[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33md9a75ec169d31f02cbe67c34f5b67086e4b8eb3f[m Se corrigio partnet controller por nueva modelo de bd - Se agrego estado de producto y precio descuento en reporte de movimientos
 app/Http/Controllers/AuxMovementController.php | 72 [32m+++++++++++++++++++++++[m[31m---[m
 app/Http/Controllers/PartnerController.php     |  6 [32m+[m[31m--[m
 app/Models/PaymentProvider/Payment.php         |  4 [32m+[m[31m-[m
 public/app/controllers/auxIndicator7Ctrl.js    | 28 [32m++++++++[m[31m--[m
 public/app/lib/AJQtable.js                     |  2 [32m+[m
 public/app/partials/Partner.html               |  6 [32m++[m[31m-[m
 6 files changed, 99 insertions(+), 19 deletions(-)
[33mc35c6fa2cb5e370706fa1bd66cedd4a52c9c7ca9[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m301728489a7346e2157cb5babc049a9fd1728118[m Se integro controladores de proveedores
 app/Http/Controllers/AuxMovementController.php |   3 [31m-[m
 public/app/controllers/PartnerPanelCtrl.js     | 115 [32m+++++++++++++++++++++++[m[31m--[m
 public/app/partials/PartnerPanel.html          |  31 [32m+++++++[m
 3 files changed, 139 insertions(+), 10 deletions(-)
[33m1ed0dfdb01ed829ce3b4d9f101f4ed4967a88a92[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m6eb9e6ee888cc563237464792d735ea23a73338e[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mc9facec9052a07d9407ea4a8a741069cfce85039[m Se removio middleware comentado e innecesario
 app/Http/Controllers/AuxProductController.php | 1 [31m-[m
 1 file changed, 1 deletion(-)
[33mfa736cefd406dacbb210f570c8e5fd3869457cce[m se corrgio fecha de controller
 app/Http/Controllers/PartnerController.php | 1 [31m-[m
 1 file changed, 1 deletion(-)
[33m183ca83436b387f8dbdb587c9a3a47cd29c3bf0d[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m149886aeb7c1308372129556c29bb508b41bac66[m se corrio contructor de proveedor
 app/Http/Controllers/PartnerController.php | 3 [32m+[m[31m--[m
 1 file changed, 1 insertion(+), 2 deletions(-)
[33ma57ac12615e02a416939fd2eb3395917e1172bb7[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m697d22c78dae559419cce455db20c10fc49d4bfa[m Se corrigio controlador de proveedores
 app/Http/Controllers/PartnerController.php | 20 [32m+++++++++[m[31m-----------[m
 1 file changed, 9 insertions(+), 11 deletions(-)
[33m08fd12390dace20bb3b3c1b269bb71f96f12a00e[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m7e4ee5a1f77df68e539a0328d2aa61ff88d9c78b[m ed
[33mb0da6f71cd8fd13c96d7277ceca2cf7ebb29bb5d[m Se corrigieron varios errores de vista
 app/Http/Controllers/AuxMovementController.php  | 89 [32m++++++++++++++++++++[m[31m---[m
 app/Http/Controllers/AuxProductController.php   |  6 [32m+[m[31m-[m
 app/Http/routes.php                             |  3 [32m+[m
 public/app/controllers/auxMovement2Ctrl.js      |  3 [32m+[m
 public/app/controllers/auxMovement2_JVECtrl.js  |  3 [32m+[m
 public/app/controllers/auxMovementOutFitCtrl.js | 12 [32m+++[m[31m-[m
 public/app/controllers/products_out.js          | 49 [32m++++++++++[m[31m---[m
 public/app/partials/products_out.html           | 96 [32m+++++++++++++++++++++[m[31m----[m
 8 files changed, 228 insertions(+), 33 deletions(-)
[33m6990288e156a870346aa89cd8ec40b36e1d4fa50[m Se corrigio filtro por color y talla en reporte de movimientos - Se agrego vista de listado de confirmacion de salida de productos
 app/Http/Controllers/AuxProductController.php |  31 [32m++[m[31m--[m
 app/Http/routes.php                           | 205 [32m+[m[31m-------------------------[m
 public/app/controllers/auxIndicator7Ctrl.js   |  23 [32m++[m[31m-[m
 public/app/controllers/auxMovementCtrl.js     |  61 [32m++++++++[m
 public/app/partials/auxIndicator7.html        |   4 [32m+[m[31m-[m
 5 files changed, 93 insertions(+), 231 deletions(-)
[33m396c65c40a23a8169263e188c1031632bf0a43af[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m851c36732dafd7dc4b69633c7f67e4198a87099a[m Se agrego opcion de sort en kardex
 public/app/controllers/auxProductCtrl.js    | 6 [32m+++[m[31m---[m
 public/app/controllers/auxProductJVECtrl.js | 6 [32m+++[m[31m---[m
 2 files changed, 6 insertions(+), 6 deletions(-)
[33me5556560a5b33e0a6437daf30d4c7a63be65d442[m Se corrigio bug en filtro de fechas por dia en indicator7
 app/Http/Controllers/AuxMovementController.php | 20 [32m++++++[m[31m-----------[m
 public/app/partials/auxIndicator7.html         | 30 [32m+++++++++++++[m[31m-------------[m
 2 files changed, 22 insertions(+), 28 deletions(-)
[33m9c62f176e1d489f3b4f285fcf1203a9eb2ddb92f[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m5e6360f230a19fc1b5d262809ea219fb6dc5b575[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experi
[33m5fb93d675ebc1614edcf122d5362a3cbaed112ed[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m940f49210f0f79131674e06647eb52af1557f12e[m Arreglo de bug de vista en auxmovement
 public/app/controllers/auxMovement2Ctrl.js     | 10 [32m+++++++[m[31m---[m
 public/app/controllers/auxMovement2_JVECtrl.js |  6 [32m++++[m[31m--[m
 public/app/controllers/auxProductCtrl.js       | 10 [32m+++++[m[31m-----[m
 public/app/controllers/auxProductJVECtrl.js    | 10 [32m+++++[m[31m-----[m
 4 files changed, 21 insertions(+), 15 deletions(-)
[33m44f2781658d5a0b7f535563f8ad851e9a2b581cc[m Se agrego motivo de retorno
 app/Http/Controllers/AuxMovementController.php   | 9 [32m+++++++[m[31m--[m
 public/app/controllers/auxMovement2Ctrl.js       | 3 [32m++[m[31m-[m
 public/app/controllers/auxMovement2_JVECtrl.js   | 3 [32m++[m[31m-[m
 public/app/controllers/auxMovementOutFit2Ctrl.js | 3 [32m++[m[31m-[m
 4 files changed, 13 insertions(+), 5 deletions(-)
[33m960caf6af44f1b09438716d551599c0c2928f4c9[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33md4798c4b06142814fd659e745d59dacdd7595db3[m Se sube prueba 3
 app/Http/Controllers/AuxMovementController.php | 2 [32m+[m[31m-[m
 1 file changed, 1 insertion(+), 1 deletion(-)
[33m932354c413355040d5d109cdd9040fafbff9bfa4[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mbbe05367509415abb382195ba044e64c578546a6[m Se sube prueba 2
 app/Http/Controllers/AuxMovementController.php | 2 [32m+[m[31m-[m
 1 file changed, 1 insertion(+), 1 deletion(-)
[33m80e9fe17ec43e9428df9f00cf8bdb177dbd070f1[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m09e4c6e538b8f000a02b5dbb3df52f77ddcda931[m Se sube prueba
 app/Http/Controllers/AuxMovementController.php | 1 [32m+[m
 1 file changed, 1 insertion(+)
[33mea857153b567f8d48a39c39a982f220cd1ee47c6[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mc18474c3af14c89f1c869b732d7c8600c7649ca6[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m373c8885a739009bef353c351c9c380a25b3e2b9[m Se agrego fecha de movimientos date
 app/Http/Controllers/AuxMovementController.php | 2 [32m+[m[31m-[m
 public/app/controllers/auxMovement2Ctrl.js     | 2 [32m+[m[31m-[m
 public/app/controllers/auxMovement2_JVECtrl.js | 2 [32m+[m[31m-[m
 3 files changed, 3 insertions(+), 3 deletions(-)
[33m7b4e6ac3f38f2345d1d2afe6b1e9708f3dad107b[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m80646ee9a46c2eef4819147c711a160f451023a1[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mc48daaac9232107c46b286934ad6c88b8924039e[m Se optimizo vista de kardex - desabilitando listado de codigos por defecto, y listando mediante boton
 public/app/controllers/auxProductCtrl.js | 6 [32m++++[m[31m--[m
 public/app/partials/auxProduct.html      | 6 [32m++++++[m
 2 files changed, 10 insertions(+), 2 deletions(-)
[33m6a11111bd9d994d6c927c790c5fa1827b281b607[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m20a7c385dc7bbb35ee211b25e77ef305ee2b662d[m Se agrego fecha de movimientos
 app/Http/Controllers/AuxMovementController.php | 2 [32m+[m[31m-[m
 public/app/controllers/auxMovement2Ctrl.js     | 3 [32m++[m[31m-[m
 public/app/controllers/auxMovement2_JVECtrl.js | 3 [32m++[m[31m-[m
 3 files changed, 5 insertions(+), 3 deletions(-)
[33m94d63df050b6255ca565ddcf256ae8109fad262c[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33mf3bc35c93d184957cfaa870aebdcbcdba19ca2e1[m Se corrigio descuento cuando se agrego producto x codigo
 public/app/controllers/auxMovementCtrl.js | 38 [32m++++[m[31m---------------------------[m
 public/app/partials/auxMovement.html      |  2 [32m+[m[31m-[m
 2 files changed, 6 insertions(+), 34 deletions(-)
[33m5f433e133d88a206c29a282b41dbfa2b322aef58[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m99282238179c68d77839212eb768ca31f39e0528[m Se agrego prueba de correcion de errores
 app/Http/Controllers/AuxMovementController.php   |  5 [32m+[m[31m-[m
 app/Models/Experimental/Product.php              |  2 [32m+[m[31m-[m
 public/app/controllers/auxMovement2Ctrl.js       | 16 [32m++++[m[31m--[m
 public/app/controllers/auxMovement2_JVECtrl.js   |  6 [32m+++[m
 public/app/controllers/auxMovementCtrl.js        | 63 [32m++++++++++++++++++[m[31m------[m
 public/app/controllers/auxMovementOutFit2Ctrl.js |  4 [32m+[m[31m-[m
 6 files changed, 73 insertions(+), 23 deletions(-)
[33m5c8f6d3a59cf34e9442518fb63865de05b55e542[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m91188b289335a8315bd2fcb490d317e5e7b79922[m Se agrego performance para AJQtable 1 y 2
 public/app/lib/AJQtable.js  | 10 [32m++++++++[m[31m--[m
 public/app/lib/AJQtable2.js |  6 [32m++++[m[31m--[m
 2 files changed, 12 insertions(+), 4 deletions(-)
[33m7ae63ff59209fce5a8faabddb0f385b0e094603f[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m4fe156ac4f5065bd48d2ca5f86a02ba4ffc38ccf[m Se corrigio filtro de fechas para reporte de movimientos - Se agrego una mejor performance para AJQtable
 app/Http/Controllers/AuxMovementController.php | 10 [32m+++++[m[31m-----[m
 public/app/controllers/PartnerCtrl.js          |  1 [31m-[m
 public/app/lib/AJQtable2.js                    |  8 [32m++++++[m[31m--[m
 3 files changed, 11 insertions(+), 8 deletions(-)
[33md415e191875cbf968419952457f5674ee5b1fd87[m Se corrigio merme de auxmovementcontroller
[33ma48e3cf6b5da437e245fb786f6bbfe9eaf6adbd8[m Se completo la version de proveedores
 app/Http/Controllers/AuxMovementController.php |  1 [31m-[m
 app/Http/Controllers/PayProviderController.php | 51 [32m+++++++++++++++++++++[m[31m-----[m
 app/Http/routes.php                            |  1 [32m+[m
 app/Models/PaymentProvider/Detail.php          |  1 [32m+[m
 app/Models/PaymentProvider/Payment.php         |  4 [32m+[m[31m-[m
 public/app/controllers/PaymentCtrl.js          |  2 [32m+[m[31m-[m
 public/app/controllers/PaymentListCtrl.js      | 37 [32m+++++++++++++[m[31m------[m
 public/app/controllers/auxMovement2_JVECtrl.js |  3 [32m+[m[31m-[m
 public/app/partials/PaymentList.html           |  6 [32m+[m[31m--[m
 resources/views/auxGod.blade.php               |  1 [32m+[m
 10 files changed, 79 insertions(+), 28 deletions(-)
[33m925fec29b59b586479203908df3ba67d96cdb8f5[m Merge branch 'experimental' of bitbucket.org:nosvenden/dashboardnosv into experimental
[33m50994b40d078c0e1e86159e6f3749e4296c37be2[m Se arreglo bug en generar salida x codigos
 public/app/partials/auxMovement.html | 4 [32m++[m[31m--[m
 1 file changed, 2 insertions(+), 2 deletions(-)
[33m6bd0ac8c63002699b70800cfc3dda4a1dffb43fa[m Se retiro restriccion de filtro por estado de movimiento en auxmovement - se agrego columna de creacion del registro
 app/Http/Controllers/AuxMovementController.php | 52 [32m+++++++++++++[m[31m-------------[m
 public/app/app.js                              |  2 [32m+[m[31m-[m
 public/app/controllers/auxIndicator7Ctrl.js    |  5 [32m++[m[31m-[m
 public/app/partials/auxIndicator7.html         |  5 [32m++[m[31m-[m
 resources/views/pdf/templatePDF.blade.php      |  1 [32m+[m
 5 files changed, 33 insertions(+), 32 deletions(-)
