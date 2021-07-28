$(document).ready(function(){

// ADMINISTRADOR
  $('.ui.form.agregarAdministrador').form({
      inline : true,
      on     : 'blur',
      fields:{
        txt_nombre: {
          rules: [
            {
              type: 'empty',
              prompt: 'Ingresa un Nombre'
            }
          ]
        },
        txt_usuario:{
          rules: [
            {
              type: 'empty',
              prompt: 'Ingresa un correo electrónico'
            },
            {
              type: 'email',
              prompt: 'Ingresa un correo electrónico válido'
            }
          ]
        }
      }
    });

// SUPERVISOR
  $('.ui.form.agregarSupervisor').form({
    inline : true,
    on     : 'blur',
    fields:{
      txt_nombres: {
        rules: [
          {
            type: 'empty',
            prompt: 'Ingresa un nombre(s)'
          }
        ]
      },
      txt_apellidoP: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_usuario:{
        rules: [
          {
            type: 'empty',
            prompt: 'Ingresa un correo electrónico'
          },
          {
            type: 'email',
            prompt: 'Ingresa un correo electrónico válido'
          }
        ]
      }
    }
  });

//PMVA
  $('.ui.form.agregarPmva').form({
    inline : true,
    on     : 'blur',
    fields:{
      txt_nombres: {
        rules: [
          {
            type: 'empty',
            prompt: 'Ingresa un nombre(s)'
          }
        ]
      },
      txt_apellidoP: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_usuario:{
        rules: [
          {
            type: 'empty',
            prompt: 'Ingresa un correo electrónico'
          },
          {
            type: 'email',
            prompt: 'Ingresa un correo electrónico válido'
          }
        ]
      }
    }
  });

// PROMOVENTE
  //DISPLAY DATE PICKER
  $('#esAdministrador').on('change',function(){
    if ($(this).prop('checked')) {
      $('#fechas_fields').css('display', 'block');
      $('.fechainicio').addClass('required');
      $('.fechafin').addClass('required');
    }else{
      $('#fechas_fields').css('display', 'none');
      $('.fechainicio').removeClass('required');
      $('.fechafin').removeClass('required');
    }
  });

  //LIMPIAR
  $('#btn_limpiarPromovente').click(function(){
    $('#fechas_fields').css('display', 'none');
    $('.fechainicio').removeClass('required');
    $('.fechafin').removeClass('required');
  });

  //DATE PICKER
  



  //VALIDACIONES
  $('.ui.form.agregarPromovente').form({
    inline : true,
    on     : 'blur',
    fields:{
      txt_nombre: {
        rules: [
          {
            type: 'empty',
            prompt: 'Ingresa un nombre o razón social'
          }
        ]
      },
      txt_display: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_rfc: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_calle: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_colonia: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_pais: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_estado: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_nombreRep: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          }
        ]
      },
      txt_usuario: {
        rules: [
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacío'
          },
          {
            type: 'email',
            prompt: 'Ingresa un correo electrónico válido'
          }
        ]
      },
      txt_fechainicio:{
        depends: 'esAdministrador',
        rules:[
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacio. Selecciona una fecha.'
          }
        ]
      },
      txt_fechafin:{
        depends: 'esAdministrador',
        rules:[
          {
            type: 'empty',
            prompt: 'Este campo no puede estar vacio. Selecciona una fecha.'
          }
        ]
      }
    }
  });

});
