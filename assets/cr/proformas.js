$(document).ready(function() {

    // let money = {};

    $(this).dataTable_ajax_es('#datatable','#tabla_data');

    // $.fn.moneyQ = function () {
    //     return this.each(function () {
    //     //     const question = JSON.parse($("#moneyq").val());
    //     });
    // }

    // Función para parsear el valor recibido y devolverlo en formato adecuado
    function parseValue(value) {
        // Verificar si el valor es undefined o null
        if (value === undefined || value === null) {
            console.error("El valor recibido no es válido.");
            return 0;  // Si no es válido, devolver 0
        }

        // Eliminar los espacios en blanco antes de evaluar (valor debería ser ya una cadena de texto)
        const trimmedValue = value.trim();

        // Si el valor no es vacío, procesamos
        if (trimmedValue !== "") {
            // Reemplazar las comas para quitar separadores de miles y convertirlo a número flotante
            const cleanedValue = trimmedValue.replaceAll(',', '');

            // Convertir el valor limpio a número flotante (decimal)
            const parsedValue = parseFloat(cleanedValue);

            // Verificar si es un número válido
            return isNaN(parsedValue) ? 0 : parsedValue;
        }

        // Si el valor es vacío o no es válido, devolver 0
        return 0;
    }




    $.fn.calcule_values = function (validated = false) {
        return this.each(function () {


            // Crear un array para almacenar los valores
            let cpaxData = [];

            // Para cada uno de los grupos de .a_cpax y .n_cpax
            $('.n_cpax').each(function(index) {
                // Obtener los valores correspondientes

                const n_cpax = parseInt($('.n_cpax').eq(index).val()) || 0; // Obtener el valor correspondiente de .n_cpax
                const a_amount = parseValue($('.a_amount').eq(index).val()) || 0; // Obtener el valor correspondiente de .a_amount

                // // Añadir el objeto con los valores al array
                cpaxData.push({ cpax: n_cpax, npax: a_amount });
            });


            // Sumar los resultados en una sola variable
            // console.log(cpaxData)
            let response = 0;

            if(validated){

                cpaxObject =  Object.entries(cpaxData.reduce((acc, obj) => {
                    // Acumulamos los valores de cada clave
                    Object.keys(obj).forEach(key => {
                        acc[key] = (acc[key] || 0) + obj[key];
                    });
                    return acc;
                }, {})).map(([key, value]) => value);
                response = (cpaxObject[0] || 0) * (cpaxObject[1] || 0); // Asegúrate de usar el valor adecuado para agencia

            }else{
                cpaxData.forEach(data => {
                    response += calcule_cpax(data.cpax, data.npax); // Asegúrate de usar el valor adecuado para agencia
                });
            }


            // Total de cpax
            const totalCpax = cpaxData.reduce((sum, data) => sum + data.cpax, 0);

            console.log(response)
            // Asignación del resultado
            $(this).asignacion_numeric('#total', response);
            $("#cpax").val(totalCpax);

            // const a_cpax     =  parseInt($("#a_cpax").val());
            // const a_amount   = parseFloat($("#a_amount").val().replaceAll(',',''));
            // const a_ag_guia= parseFloat($("#agence_guia").val().replaceAll(',',''));
            //
            //
            // const n_cpax        = parseInt($("#n_cpax").val());
            // const n_amount      = parseFloat($("#n_amount").val().replaceAll(',',''));
            // const n_ag_amount = parseFloat($("#agence_amount").val().replaceAll(',',''));
            //
            // response = 0;
            //
            // const calcule_1 = calcule_cpax(a_cpax,a_amount,agence_guia);
            // const calcule_2 = calcule_cpax(n_cpax,n_amount,agence_amount);
            //
            // response = calcule_1 + calcule_2;
            // calcule_pax = (!isNaN(a_cpax) ? a_cpax : 0) + (!isNaN(n_cpax) ? n_cpax : 0) + (!isNaN(agence_guia) ? agence_guia : 0);
            //
            // $(this).asignacion_numeric('#total',response);
            // $("#cpax").val(calcule_pax)

        });
    }

    const calcule_cpax = function (cpax,amount) {

            response = 0;
             cpax = (cpax === 0) ? 1 : cpax;
            // if((cpax !== '') && (amount !== '' && amount > 0)){
            if((cpax !== '' && cpax > 0) && (amount !== '' && amount > 0)){
                response = amount*cpax;
            }else{
                response = 0;
            }
            return response;
    }



    $.fn.active_money = function () {
        return this.each(function () {
            const check = $(this).is(':checked');
            const input = $("#video_money");
            input.prop('readonly', !check);
        });
    }

    // $.fn.real_money = function () {
    //     return this.each(function () {
    //        let mone = $("#money").val() || 1;
    //        const question = JSON.parse($("#moneyq").val());
    //
    //         money = question[mone];
    //         // console.log('xxxx ', money)
    //         // console.log(money.language)
    //     });
    // }

    $.fn.add_rooms = function (idQ = null,data = []) {
        return this.each(function () {
            const field  = idQ || $("#room").val();
            const table = $("#table_rooms");


            //filter
            const filter = data.filter((item) => item.r_id == field)['0'];
            if(field != '' && filter != undefined && $(`#table_rooms tr[data-id="${field}"]`).length == 0){

                    const result = filter;

                    const priceCalc = (parseFloat(result.r_price)+((result.r_price*13)/100)).toLocaleString('en-US', { minimumFractionDigits: 2 });

                        const template = `
                        <tr data-id="${result.r_id}">
                            <td>
                                <input type="text" name="room[pax][]" id="pax" data-id="${result.r_id}" class="form-control text-center rooms" maxlength="4" value="1" onkeyup="$(this).change_price('.rooms')">
                            </td>
                            <td>
                                <input type="text" name="room[size][]" id="size" data-id="${result.r_id}" class="form-control text-center rooms" maxlength="4" value="1" onkeyup="$(this).change_price('.rooms')">
                                <input type="hidden" name="room[id][]"  value="${result.r_id}" class="form-control text-center">
                            </td>
                            <td>${result.r_name}</td>
                            <td><input type="text" name="room[price][]" id="price" value="${result.r_price}" data-id="${result.r_id}" class="form-control dinero text-center rooms" onkeyup="$(this).change_price('.rooms')"></td>
                            <td><input type="text" name="room[iva][]" id="iva" value="13" data-id="${result.r_id}" class="form-control numero text-center rooms" minlength="1" maxlength="3" onkeyup="$(this).change_price('.rooms')"></td>
                            <td class="rooms" data-id="${result.r_id}"><b class="total">${priceCalc}</b></td>
                            <td class="deleteAll"><a href="javascript:void(0)" onclick="$(this).delete_tr('${result.r_id}','#table_rooms','.rooms')"><i class="fas fa-times fs-30"></i></a></td>
                        </tr>
                        `;
                        table.append(template);
                        $(this).numeros_func('.numero');
                        $(this).dinero_func('.dinero');
                        $(this).calculeTotal('.rooms');
            }
            $("#room").val('');

        });
    }

    $.fn.add_package = function (data = null,editable = false) {
        return this.each(function () {

            // const idQ =  null;
            const field  = data != null ? data.pfp_package : $("#package").val();

            if(field != ''){

                if(data !== null && Object.keys(data).length > 0){
                    // console.log('entro error')
                    const dataArr = {
                        id: data.pfp_package,
                        title: data.p_title,
                        description: data.pfp_description,
                        price: data.pfp_price,

                    };
                    // console.log(data)
                    $(this).dataProforma(dataArr, editable);
                }else{
                    // llamar al ajax

                    $(this).simple_call_text({id : field},'url_package_get',false,(data) =>{
                        const result = data.response;
                        if(result.success == 1  && $(`#table_package tr[data-id="${field}"]`).length == 0){

                            // console.log(result)

                            const dataArr = {
                                id: result.data.p_id,
                                title: result.data.p_title,
                                description: result.data.p_description,
                                price: result.data.p_price,

                            }
                            $(this).dataProforma(dataArr, editable);

                        }

                        $("#package").val('');
                    },true,false);

                }

            }
        });
    }



    $.fn.dataProforma = function (data,editable) {
        return this.each(function () {
            const table = $("#table_package");
            // console.log(data)
            let id = data.id;
            const editablefield = editable ? `<td id="editor1" class="description" data-id="${id}" contenteditable="true">${data.description}</td>` :`<td class="description" >${data.description}</td>`;
            const template = `
                        <tr data-id="${id}">
                            <td>
                                <input type="text" name="package[size][]" id="size" data-id="${id}" class="form-control text-center package" maxlength="4" value="1" onkeyup="$(this).change_price('.package')">
                                <input type="hidden" name="package[id][]"  value="${id}" class="form-control text-center">
                                <textarea  name="package[description][]"  class="form-control text-center d-none txtdescription"></textarea>
                            </td>
                            <td>${data.title}</td>
                            ${editablefield}
                            <td class="element_hidde" style=" width: 160px !important; "><input type="text" name="package[price][]" id="price" value="${data.price}" data-id="${id}" class="form-control dinero text-center package" style=" width: 160px !important; " onkeyup="$(this).change_price('.package')"></td>
                            <td class="element_hidde package" data-id="${id}" style=" width: 160px !important; "><b class="total">${data.price}</b></td>
                            <td class="deleteAll"><a href="javascript:void(0)" onclick="$(this).delete_tr('${id}','#table_package','.package')"><i class="fas fa-times fs-30"></i></a></td>
                        </tr>
                        `;
                table.append(template);
                $(this).dinero_func('.dinero');
                $(this).calculeTotal('.package');
        });
    }


    // $.fn.send_email_proforma = function (id) {
    //     return this.each(function () {
    //
    //         if(id != ''){
    //             Swal.fire({
    //                 title: '¿Estas seguro de enviar por correo la proforma?',
    //                 text: "¡Se enviara el correo electronico al cliente!",
    //                 icon: 'warning',
    //                 showCancelButton: true,
    //                 confirmButtonColor: '#d33',
    //                 cancelButtonColor: '#3085d6',
    //                 cancelButtonText: 'Cancelar',
    //                 confirmButtonText: 'Enviar'
    //             }).then((result) => {
    //                 if (result.value) {
    //
    //                     $(this).simple_call_text({id},'url_booking_proforma_email');
    //
    //                 }
    //             })
    //         }
    //     });
    // }

    $.fn.convert_proforma = function (id) {
        return this.each(function () {

            if(id != ''){
                $(this).simple_call_text({id},'url_convert_proforma');
            }
        });
    }

    $.fn.change_price = function (search = '',idQ  = null) {
        return this.each(function () {
            const input = $(this);
            const id = (idQ != null) ? idQ : input.attr('data-id');

            if(id != ''){

                const pax = $(`${search}[data-id="${id}"]#pax`).val() || 1;
                const size = $(`${search}[data-id="${id}"]#size`).val() || 1;
                const iva = $(`${search}[data-id="${id}"]#iva`).val() || 0;

                const price = $(`${search}[data-id="${id}"]#price`).val().replaceAll('$','').replaceAll(',','').replaceAll(/\s/g, '');

                //calcule
                const calcule =   pax *(size * price);
                // const calcule =  (size * price+((price*iva)/100))*pax;
                const total = (price > 0) ? calcule.toLocaleString('en-US', { minimumFractionDigits: 2 }) : 0;
                $(`${search}[data-id='${id}']>b.total`).text(total)
                $(this).calculeTotal(search, size);
            }
        });
    }


    $.fn.change_price_manual = function (idI) {
        return this.each(function () {
            const input = $(`#size[data-id='${idI}']`);
            const id = input.attr('data-id');

            if(input.val() != '' && input.val() > 0){
                const price = $(`table tbody>tr[data-id="${id}"]>#price`).text().replaceAll('$','').replaceAll(',','').replaceAll(/\s/g, '');
                const total = (price > 0) ?  (input.val() * price).toLocaleString('en-US', { minimumFractionDigits: 2 }) : 0;
                $(`table tbody>tr[data-id="${id}"]>.total`).text(total);

                $(this).calculeTotal();
            }
        });
    }

    function cleanNumber (number) {
        // console.log(number)
        return number.replaceAll('$','').replaceAll(',','').replaceAll(/\s/g, '');
    }

    $.fn.calculeTotal = function (search = '',count = 1) {
        return this.each(function () {
                let subtotal = 0;
                let calcule1 = 0;
                let calcule3 = 0;
                let calculeI = 0;
                let calcule = 0;

                let calculo_final_subtotal = 0;
                let calculo_final_iva = 0;
                let calculo_final_total = 0;


            const tableSearch = $(`${search}_table>tr`);
            tableSearch.map((r,v) => {
                const atr = $(v);
                const id = atr.attr('data-id');

                const pax    = ($(`${search}[data-id="${id}"]#pax`).length > 0) ? cleanNumber($(`${search}[data-id="${id}"]#pax`).val())  : 1;
                const size   = ($(`${search}[data-id="${id}"]#size`).val() !== undefined) ? cleanNumber($(`${search}[data-id="${id}"]#size`).val())  : 1;
                const price  = ($(`${search}[data-id="${id}"]#price`).val() !== undefined) ? cleanNumber($(`${search}[data-id="${id}"]#price`).val()) : 0;
                const iva    = ($(`${search}[data-id="${id}"]#iva`).length > 0) ? cleanNumber($(`${search}[data-id="${id}"]#iva`).val()) : 0;

                //calcule
                var subtotal_siniva =  pax * (size * price);
                var calculo_iva = ((subtotal_siniva*iva)/100)
                var calculo_total_iva = subtotal_siniva+((subtotal_siniva*iva)/100)
                // console.log({
                //     subtotal_siniva,
                //     calculo_iva,
                //     calculo_total_iva,
                // })


                calculo_final_subtotal += subtotal_siniva;
                calculo_final_iva += calculo_iva;
                calculo_final_total += calculo_total_iva;

                // const calcule =  pax * (size * price);
                //
                // subtotal += calcule;
                // calculeI += (subtotal*iva)/100;

            })
            calcule1 += (subtotal+calculeI);


                $(`${search}>#subtotal`).text(calculo_final_subtotal.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                $(`${search}>#ivatotal`).text(calculo_final_iva.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                $(`${search}>#total`).text(calculo_final_total.toLocaleString('en-US',  { minimumFractionDigits: 2 }));


                //codigo independiente que toma todos los totales final y los calcula
                $('.totalCalc').each(function (index, item){
                    const priceC = cleanNumber(item.textContent);
                    if(parseFloat(priceC) != NaN && parseFloat(priceC) > 0 && priceC != ''){
                        calcule3 += parseFloat(priceC);
                    }
                    calcule++;
                })
                $('#totalP').text(calcule3.toLocaleString('en-US', { minimumFractionDigits: 2 }));


                // $(`.total`).each(function (index, item){
                //     calcule += parseFloat(item.textContent.replaceAll('$','').replaceAll(',','').replaceAll(/\s/g, ''));
                // })
        });
    }



    $.fn.delete_tr = function (id,table,input = '') {
        return this.each(function () {
            // const rr = filialAssigne.filter(function (item, index) {
            //     if(item.id == id){
            //         filialAssigne.splice(index, 1);
                    $(`${table} tr[data-id="${id}"]`).remove()
            if(input == ''){
                    $(this).calculeTotal(input);
            }
                // }
            // })
            // console.log(filialAssigne)
        });
    }

    $.fn.reservaHiden = function () {
        return this.each(function () {
            const reservaA          = $('#type').val();
            const hideAll           = $('.hide_all');
            const agence_div        = $('.agence_div').hide();
            const  required_all     = $('.required_all');
            let seleer              = $('#seleer');

            // console.log('Cantidad: ' + reservaA)

            if(reservaA != ''){
                if(reservaA == 1){
                    hideAll.hide();
                    required_all.attr('required',false);
                } else if(reservaA == 4){
                    hideAll.show();
                    agence_div.show();
                    required_all.attr('required',true);
                } else{
                    hideAll.show();
                    required_all.attr('required',true);
                }
            }



        });
    }


    $.fn.selectReserva = function (formsdiv = '#frm_data') {
        return this.each(function () {
            const id         = $("#type").val();
            // var rooms =  $('.rooms');
            const dayE       = $("#day").val() || 1;
            const daysButton = $("#daysButton");
            const agence = $("#agence_div").hide();

            //cada que cambie eliminar todo y volverlo a crear
            // $("#table_days > .dayselement").remove();

            $("#day").val(dayE);
            if(id == 3) {
                // daysButton.attr('disabled',false);
                $(this).days_generator(true, true);
                $(".hiddenall").hide();

                $('.rooms').each(function () {
                    $(this).find('option:contains("Restaurante")').first().prop('selected', true).change();
                });
                $('.rooms').attr('readonly', true);

            }else if(id == 4){
                agence.show();


                daysButton.attr('disabled',false);
                $(this).days_generator(true);
                $(".hiddenall").show();


                // $('.rooms').val('').change();
                $('.rooms').attr('readonly',false);

            }else{
                // $("#day").val('');
                // dayE.attr('readonly',false);
                daysButton.attr('disabled',false);
                $(this).days_generator(true);
                $(".hiddenall").show();


                // $('.rooms').val('').change();
                $('.rooms').attr('readonly',false);

                $(this).calcule_values()
            }

            $(this).validateFields();
            $(formsdiv).validator('destroy');  // Destruir el validador actual
            $(formsdiv).validator();  // Volver a inicializar el validador
        });
    }







    $.fn.days_generator = function (loadx = false,hide = false,special = false) {
        return this.each(function () {
            let days = $("#day").val();
            // console.log();

            //contar primero cuantos elementos existen si son >= intentar agregar
            //si son menores < a la cantidad actual eliminar de atras

            let htmladd = $("tbody#table_days > .dayselement");

            //count actual
            let countActual = htmladd.length;
            let dwhile = countActual +1;
            // console.log((countActual || 0))
            // console.log('dwhile: ' , dwhile)
            console.log('countActual - ', countActual)


            let templateRoom = '';
            let roomJ =  window.roomsJson;
            roomJ.map(function(r){
                templateRoom += `<option value="${r.r_id}">${r.r_name}</option>`
            })

            if(days == ''){
            // if(days == '' || days < 1){
                if(loadx == false )  $(this).mensaje_alerta(1,'Debe ingresar un numero de dias valido')
                return false;
            }

            console.log('D-While - ' , dwhile)

            if(days >= (countActual || 0)){
                let calculate = days-countActual;
                // console.log('calculate: ' , calculate);
                $(this).gay_crud_create(calculate,dwhile,hide,templateRoom,special);
                //create element
                // console.log('crear elemetnos')
            }else{
                // delete element
                let calculate = countActual-days;
                // console.log('calculate: ' , calculate);
                $(this).gay_crud_remove(countActual,calculate,special);
                // console.log('delete element')

            }




            // ;


            //module generaye days


            $(this).fecha_func('.fecha','yyyy-mm-dd',true);
            $(this).numeros_func('.numero');
            // $('.days').attr('required',true);

        });
    }

    $.fn.gay_crud_remove = function (count,calculate) {
        return this.each(function () {
            // let htmladd = $("#table_days");
            // let template = '';

            console.log({count:count,calculate:calculate});
            // console.log('daysX ', days)
            for (let i = count; i > count-calculate;i--) {
                $("#table_days > .dayselement")[i-1].remove()
                $(`#table_days > #son_${i}`).remove()
                console.log('i----' + i);
            }

        })
    };

    $.fn.gay_crud_create = function (days,d,hide, templateRoom,special) {
        return this.each(function () {
            let htmladd = $("#table_days");

            let template = '';
            console.log('xxx - ' , d)
          //  var agenceTemplate = '';


            // console.log('daysX ', days)

            for (let i = 1; i <= days;i++) {

                let agenceTemplate = generateSelect(d)
                // console.log(i)
                if(hide){
                    let template = `
                            <tr class="dayselement" id="father_${d}" data-id="${d}">
                                <td>${d}</td>
                                <td><input type="text" id="day_${d}" data-id="${d}" class="form-control form-control-sm text-center fecha days all_requireds" name="days[${d}][date]"></td>
                                <td><input type="time" id="hour1_${d}" class="form-control form-control-sm text-center days all_requireds"  name="days[${d}][hour1]"></td>
                                <td  class="hiddenall"><input type="time" id="hour2_${d}" class="form-control form-control-sm text-center hiddenall"  name="days[${d}][hour2]"></td>
                                <td class="rooms_all"><select id="room_${d}" class="form-control form-control-sm text-center days rooms all_requireds"  name="days[${d}][room]"><option value="" selected disabled>[SELECCIONAR]</option>${templateRoom}</select></td>
                                ${agenceTemplate}
                                <td><input type="text" id="observation_${d}" class="form-control form-control-sm text-center days"  name="days[${d}][observation]"></td>
                                <td><button type="button" data-id="${d}" class="btn btn-dark btn-sm disabled all_button" id="btn_${d}" onclick="$(this).validate_orDays()"><i class="fas fa-check"></i></button></td>
                                
                            </tr>
                         `;
                    htmladd.append(template);
                }else{
                    let template = `
                            <tr class="dayselement" id="father_${d}" data-id="${d}">
                                <td>${d}</td>
                                <td><input type="text" id="day_${d}" data-id="${d}" class="form-control form-control-sm text-center fecha days all_requireds" required name="days[${d}][date]"  onchange="$(this).validate_or_Day()"></td>
                                <td><input type="time" id="hour1_${d}" data-id="${d}"  class="form-control form-control-sm text-center days all_requireds"  required name="days[${d}][hour1]" onchange="$(this).validate_or_Day()"></td>
                                <td class="hiddenall"><input type="time" data-id="${d}"  id="hour2_${d}" class="form-control form-control-sm text-center days hiddenall"  required name="days[${d}][hour2]" onchange="$(this).validate_or_Day()"></td>
                                <td class="rooms_all"><select id="room_${d}"  data-id="${d}" class="form-control form-control-sm text-center days rooms all_requireds"  name="days[${d}][room]"  onchange="$(this).validate_or_Day()"><option value="" selected disabled>[SELECCIONAR]</option>${templateRoom}</select></td>
                                ${agenceTemplate}
                                <td><input type="text" id="observation_${d}" class="form-control form-control-sm text-center days"  name="days[${d}][observation]"></td>
                                <td><button type="button" data-id="${d}" class="btn btn-dark btn-sm disabled all_button" id="btn_${d}" onclick="$(this).validate_orDays()"><i class="fas fa-check"></i></button></td>
                            </tr>
                         `;

                        if(special == true){
                            template += `
                             <tr id="son_${d}">
                                    <td colspan="7"><div class="row food_especial" id="food_especial_${d}"></div></td>
                             </tr>`;

                        }
                    htmladd.append(template);

                    $(this).generate_food_especia_title(d);

                }
                d++;
            }
        })
    };

    function generateSelect(d = 0){
        const type  = $("#type").val();

        if(type == 4){
            // console.log(typeBox)
            const typeBoxObjects = Object.keys(typeBox).map(key => ({
                id: key,
                name: typeBox[key]
            }));

            // console.log(typeBoxObjects)
            var   agenceTemplate = `<td class="agence-select"> <select name="days[${d}][box_type]" id="type_${d}" class="form-control form-control-sm text-center days all_requireds"><option value="" selected disabled>[ SELECCIONAR ]</option>  ${typeBoxObjects.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}</select>  </td>
                                   <td class="agence-select"> <input type="text" name="days[${d}][box_account]" id="account_${d}" class="form-control form-control-sm days all_requireds"> </td>`;

        }

        console.log({type : type, agenceTemplate : agenceTemplate});

        return agenceTemplate;

    }

    $.fn.validateFields = function () {
        return this.each(function () {
            $('.all_requireds').attr('required',true);
            $('#frm_data').validator('destroy');  // Destruir el validador actual
            $('#frm_data').validator();  // Volver a inicializar el validador
        });
    }

   $.fn.updateAgenceSelect = function (i) {
       return this.each(function () {
           const type  = $("#type").val();
            $("#table_days .dayselement").each(function() {
                const row = $(this);
                let id = row.attr('data-id');
                console.log('Elements : ', row)

                if (type == 4) {
                    // Si `type` es 4, agregamos el `td` con el select e input después del `td.rooms_all`
                    if (row.find('.agence-select').length === 0) {
                        const agenceTemplate = generateSelect(id); // Generar el select e input
                        row.find('.rooms_all').after(agenceTemplate);  // Agregar los nuevos `td`
                        $(this).validateFields();
                    }
                } else {
                    // Si `type` no es 4, eliminamos los `td` con la clase .agence-select
                    row.find('.agence-select').remove();
                }
            });
       });
    }

    $.fn.generate_food_especia_title = function (i) {
        return this.each(function () {
             let generator = $(`#food_especial_${i}`);
            let days = '';

            if(generator.length == 1){

            let template = `
                <div class="col-6  border-end">
                      <div class="row">
                          <div class="col">
                              <h5 class="text-center"><a href='javascript:void(0)' onclick="$(this).add_services('${i}')">Servicio de Eventos <i class="fas fa-plus"></i></a> </h5>
                          </div>
                      </div>
                      <div class="row mb-3">
                             <div class="col text-center">Descripción</div>
                             <div class="col-3  text-center">Tiempo</div>
                             <div class="col-1  text-center">Del</div>
                      </div>
                      
                      <div id="details_events_${i}"></div>
                </div>

                <div class="col-6">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-center"> <a href='javascript:void(0)' onclick="$(this).add_especial('${i}','${days}')">Menu Especial <i class="fas fa-plus"></i></a>  </h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col  text-center">Nombre Usuario</div>
                        <div class="col  text-center">Menu</div>
                        <div class="col-1 text-center">Del</div>
                    </div>
                     <div id="details_especials_${i}"></div>
            </div>
            `;
            generator.append(template);
            // console.log("#food_especial_" + i)
            }
        })
    };


    $.fn.validate_orDays = function () {
        return this.each(function () {

            let id = $(this).data('id');
            let getDay = $(`#day_${id}`).val();
            let data = window.resultDays;


            //filter Vector Global
            // console.log(data)
            // console.log(getDay)
            let search = data.filter(r => {
               return r.bd_day == getDay;
            })

            $(this).Sawl_alert_event(search);
            // console.log(search)

            // console.log('date', this);
            // console.log(window.resultDays);
        })
    };

    $.fn.Sawl_alert_event = function(days = []) {
        return this.each(function () {

            let table = '';

            let i = 1;
            console.log(days)
            days.forEach( r => {

                table +=  `
                <tr>
                <td>${i++}</td>
                <td>${r.b_event_name}</td>
                <td>${r.bd_day}</td>
                <td>${r.bd_houri}</td>
                <td>${r.bd_hourf}</td>
                </tr>
                `
            });


            Swal.fire({
                html:true,
                title:'<i>Días Reservados</i>',
                customClass: 'swal-wide800',
                html:`<table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nombre E.</th>
                          <th scope="col">Fecha</th>
                          <th scope="col">Hora inicial</th>
                          <th scope="col">Hora final</th>
                        </tr>
                      </thead>
                      <tbody>
                        ${table}
                      </tbody>
                    </table>
           `});
        })
    }


    $.fn.validate_or_Day = function() {
        return this.each(function () {

            var dataId = $(this).data('id');
            let data_id = $('#data_id').val();
            let day = $(`#day_${dataId}`).val();
            let hour1 = $(`#hour1_${dataId}`).val();
            let hour2 = $(`#hour2_${dataId}`).val();
            let room = $(`#room_${dataId}`).val();
            window.resultDays = [];
            if((day != '' && day != undefined) && (hour1 != '' && hour1 != undefined) && (hour2 != '' && hour2 != undefined) && (room != '' && room != undefined)){
                $(this).simple_call_text({day,hour1, hour2,data_id,room},'url_validate_date',false,function(result){
                    let resp = result.response;

                    //clear all
                    $(`#btn_${dataId}`).removeClass('btn-success')
                    $(`#btn_${dataId}`).addClass('disabled');//all Buttons Hiddens
                    $(`#btn_${dataId}`).addClass('btn-dark');//all Buttons Hiddens

                    if(resp.success == 2) {
                        window.resultDays = resp.data;

                        $('#days_events').get(0).scrollIntoView({behavior: 'smooth'});
                        $(this).mensaje_alerta(1, "Debes validar las fechas se encontraron coincidencia con otros Eventos");


                        $('.fecha').each(function(){
                            let days = $(this).val();
                            if(window.resultDays.some(r => r.bd_day == days)){

                                // Button ID
                                //Enable All Buttons
                                // console.log('existe' , dataId);
                                $(`#btn_${dataId}`).removeClass('disabled')
                                $(`#btn_${dataId}`).removeClass('btn-dark');//remove all dark
                                $(`#btn_${dataId}`).addClass('btn-success');//all Buttons Hiddens
                            }
                        })
                    }else{

                    }

                },true,true);

                // console.log('call me')
            }
            // else{
            //     console.log('vacio')
            // }
        })
    }


    $.fn.days_data = function (data = {} ,i  = 1) {
        return this.each(function () {
            console.log(data)
            $(`.days#day_${i}`).val(data.bd_day);
            $(`.days#hour1_${i}`).val(data.bd_houri);
            $(`.days#hour2_${i}`).val(data.bd_hourf);
            $(`.days#room_${i}`).val(data.bd_room).change();
            $(`.days#observation_${i}`).val(data.bd_description);

            //valite exist box element
            if($(`.agence-select`).length != 0){
                $(`.days#type_${i}`).val(data.bd_type)
                $(`.days#account_${i}`).val(data.bd_account);
            }
        });
    }


    $.fn.converter = function(id = '') {
        return this.each(function() {
            if(id == ''){
                $(this).mensaje_alerta(1, 'Lo Sentimos el evento no se puede cerrar');
            }else{
                Swal.fire({
                    title: '¿Estas seguro de convertir a una proforma?',
                    text: "¡No podras revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Convertir'
                }).then((result) => {
                    if (result.value) {

                        $(this).simple_call_text({id},'url_booking_convert',false,(err) =>{
                            window.location.reload();
                            // return true;
                        },true);

                    }
                })

            }
        })
    }

    $.fn.showProformas = function() {
        return this.each(function() {

            const typeE = [
                'RE: ',
                'RE: ',
                'PF: ',
                '',
            ];

            $(this).simple_call_text({"send" : "send"},'url_event_proforma',false,(content) =>{
                // return true;
                let result = content.response;
                let data = [];
                let color = [];
                if(result.success == 1) {
                    result.data.forEach(function (item) {

                        //clean color
                        if (color[item.r_id] == undefined) {
                            color[item.r_id] = (Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, '0');
                        }

                        //day eachs
                        calendar.addEvent({
                            id: item.r_id,
                            title: typeE[item.module-1] + ' ' + item.r_name,
                            start: item.pf_date,
                            color: item.r_color,
                            groupId: item.module
                        });
                    });
                }


            },true);
            //

        })
    }



});
