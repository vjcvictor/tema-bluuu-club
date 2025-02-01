// const elementoGuia = document.getElementById('comprar-ahora-boton'); 
// const botonMovil = document.getElementById('comprar-ahora-boton-movil');

// window.addEventListener('scroll', () => {
//   const posicion = elementoGuia.getBoundingClientRect(); 
//   if (posicion.top <= -31.4375) {
//     botonMovil.classList.add('activo'); 
//   } else {
//     botonMovil.classList.remove('activo');
//   }
// });


// jQuery(document).ready(function($) {
//   // Abrir la modal al hacer clic en el botón "Comprar ahora"
//   $('body').on('click', '.comprar-ahora', function(e) { // Usamos 'body' para eventos delegados
//     e.preventDefault();
//     var productId = $(this).data('product-id');

//     // Obtener el precio del producto y mostrar la modal
//     $.ajax({
//       url: '?wc-ajax=woostify_child_get_product_data', // Endpoint de WooCommerce para obtener datos del producto
//       type: 'POST',
//       data: {
//         product_id: productId
//       },
//       success: function(response) {
//         var precioUnitario = parseFloat(response.price);
//         var nombreProducto = response.name;

//         // Construir el contenido de la modal
//         var modalContent = `
//           <h2>Completa tu compra de ${nombreProducto}</h2>
//           <label for="cantidad">Cantidad:</label>
//           <select id="cantidad" name="cantidad">
//             <option value="1">1</option>
//             <option value="2">2</option>
//             <option value="3">3</option>
//           </select>
//           <p>Precio total: <span id="precio-total">${precioUnitario}</span></p>
//           <div id="formulario-pago-container">
//             Cargando formulario...
//           </div>
//         `;

//         // Mostrar la modal con el contenido
//         $('#modal-pago .modal-contenido').html(modalContent);
//         $('#modal-pago').show();

//         // Cargar el formulario de pago de WooCommerce via AJAX
//         $.ajax({
//           url: '?wc-ajax=get_checkout_form', // Endpoint (a crear) para obtener el formulario de pago
//           type: 'POST',
//           data: {
//             product_id: productId
//           },
//           success: function(response) {
//             $('#formulario-pago-container').html(response);
//             // Inicializar el comportamiento de WooCommerce en el formulario (si es necesario)
//             // ...
//           }
//         });

//         // Actualizar el precio total al cambiar la cantidad
//         $('#cantidad').change(function() {
//           var cantidad = $(this).val();
//           var precioTotal = cantidad * precioUnitario;
//           $('#precio-total').text(precioTotal);
//         });
//       }
//     });
//   });

//   // Cerrar la modal
//   $('body').on('click', '.cerrar-modal', function() { // Usamos 'body' para eventos delegados
//     $('#modal-pago').hide();
//   });
// });