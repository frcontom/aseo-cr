$(document).ready(function() {

    let all_users = $("#all_users");

    $(this).simple_call_text({'date': '2022-10'},'url_calcule',false,(response) =>{
        let data = response.response


        let divs = data.data.all_divs;
        let users = data.data.all_users;
        let amounts = data.data.all_amount;
        console.log(amounts)

        let amoun = [];

        amounts.forEach(amount => {
            amoun.push( parseInt(amount.total_eventos) )
        })

        console.log(divs)
        //show divs
        $("#div_1").text(divs.total_reserva)
        $("#div_2").text(divs.total_proforma)
        $("#div_3").text(divs.total_eventos)
        $("#div_4").text(divs.total_canceladas)


        if(jQuery('#barChart_1').length > 0 ){
            const barChart_1 = document.getElementById("barChart_1").getContext('2d');

            barChart_1.height = 100;

            new Chart(barChart_1, {
                type: 'bar',
                data: {
                    defaultFontFamily: 'Poppins',
                    labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul","Ago","Sep","Oct","Nov","Dec"],
                    datasets: [
                        {
                            label: "Eventos",
                            data: amoun,
                            borderColor: 'rgba(1,163,255,1))',
                            borderWidth: "0",
                            backgroundColor: 'rgba(41,83,232,1)',
                            barThickness: 50,
                        }
                    ]
                },
                options: {
                    plugins:{
                        legend: false,
                    },
                    scales: {
                        y: {
                            ticks: {
                                beginAtZero: true
                            }
                        },
                        x: {
                            // Change here
                            barPercentage: 0.5
                        }
                    }
                }
            });
        }







        let template_user = '';
        users.forEach(user => {

            template_user += `
                <tr>
                <td><h4 class='title font-w600 mb-2'>${user.user_name}</h4></td>
                <td>${user.total_restaurante}</td>
                <td>${user.total_reserva}</td>
                <td>${user.total_proforma}</td>
                <td>${user.total_eventos}</td>
                <td>${user.total_canceladas}</td>
                </tr>
            `;
        })
        all_users.empty().append(template_user);
    },true);
})




// (function($) {
//
//
//
//
//
//
//     /* function draw() {
//
//     } */
//
//     var dzSparkLine = function(){
//         //let draw = Chart.controllers.line.__super__.draw; //draw shadow
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//         var barChart1 = function(){
//         }
//
//
//
//
//
//         /* Function ============ */
//         return {
//             init:function(){
//             },
//
//
//             load:function(){
//                 barChart1();
//             },
//
//
//         }
//
//     }();
//
//
//
//     jQuery(window).on('load',function(){
//         dzSparkLine.load();
//     });
//
//     jQuery(window).on('resize',function(){
//         //dzSparkLine.resize();
//         setTimeout(function(){ dzSparkLine.resize(); }, 1000);
//     });
//
// })(jQuery);