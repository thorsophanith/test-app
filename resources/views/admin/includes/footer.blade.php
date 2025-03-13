<div class="bottom-page">
    <div class="body-text">Copyright © 2024 SurfsideMedia</div>
</div>



    <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

    document.addEventListener("DOMContentLoaded", function() {
    var searchInput = document.getElementById("search-input");
    var boxContentSearch = document.getElementById("box-content-search");

    searchInput.addEventListener("keyup", function() {
        var searchQuery = searchInput.value;

        if (searchQuery.length > 2) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "{{ route('admin.search') }}?query=" + encodeURIComponent(searchQuery), true);
            xhr.responseType = 'json';

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // Clear previous results
                    boxContentSearch.innerHTML = '';

                    var data = xhr.response;
                    data.forEach(function(item) {
                        var url = "{{ route('admin.product.edit',['id'=>'product_id'])}}";
                        var link = url.replace('product_id', item.id);

                        var listItem = document.createElement("li");
                        listItem.innerHTML = `
                            <ul>
                                <li class="product-item gap14 mb-10">
                                    <div class="image no-bg">
                                        <img src="{{ asset('uploads/products/thumbnails') }}/${item.image}" alt="${item.name}" class="imged">
                                    </div>
                                    <div class="flex items-center justify-between gap20 flex-wrap">
                                        <div class="name">
                                            <a href="${link}" class="body-text">${item.name}</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-10">
                                    <div class="divider"></div>
                                </li>
                            </ul>
                        `;
                        boxContentSearch.appendChild(listItem);
                    });
                }
            };

            xhr.onerror = function() {
                console.error('There was an error with the request.');
            };

            xhr.send();
        }
    });
});



(function () {
    var tfLineChart = (function () {

        var chartBar = function () {
            var options = {
                series: [{
                    name: 'Total',
                    data: [{{ $AmountM ?? 0 }}]
                }, {
                    name: 'Pending',
                    data: [{{ $OrderedAmountM ?? 0 }}]
                },
                {
                    name: 'Delivered',
                    data: [{{ $DeliveredAmountM ?? 0 }}]
                }, {
                    name: 'Canceled',
                    data: [{{ $CanceledAmountM ?? 0 }}]
                }],
                chart: {
                    type: 'bar',
                    height: 325,
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '10px',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false,
                },
                colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                stroke: {
                    show: false,
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: '#212529',
                        },
                    },
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                },
                yaxis: {
                    show: false,
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "$ " + val + "";
                        }
                    }
                }
            };

            var chartElement = document.querySelector("#line-chart-8");
            if (chartElement) {
                var chart = new ApexCharts(chartElement, options);
                chart.render();
            }
        };

        return {
            init: function () { },

            load: function () {
                chartBar();
            },
            resize: function () { },
        };
    })();

    document.addEventListener("DOMContentLoaded", function () {
        // Initialization related code can go here if needed
    });

    window.addEventListener("load", function () {
        tfLineChart.load();
    });

    window.addEventListener("resize", function () {
        // Handle window resize if needed
    });
})();




        // =====Add Brand=====

            $(function(){
                $("#myFile").on("change",function(e){
                    const photoInp = $("#myFile");
                    const [file] = this.files;
                    if (file) {
                        $("#imgpreview img").attr('src',URL.createObjectURL(file));
                        $("#imgpreview").show();
                    }
                });

                $("#gFile").on("change",function(e){
                    const photoInp = $("#gFile");
                    const gphotos = this.files;
                    $.each(gphotos,function(key,val){
                        $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}" /></div>`)
                    })
                });

                $("input[name='name']").on("change",function(){
                    $("input[name='slug']").val(StringToSlug($(this).val()));
                });

            });
            function StringToSlug(Text) {
                return Text.toLowerCase()
                .replace(/[^\w ]+/g, "")
                .replace(/ +/g, "-");
            }


//  ==========delete alert=========

$(function() {
        $('.delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this record?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });




 

    </script>
</body>
</html>