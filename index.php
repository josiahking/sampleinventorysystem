<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Coalition Technology Inventory System</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <section id="main" class="container">
            <section id="form" class="row">
                <div class="col-lg-12 col-md-12 mb-5 mt-5">
                    <h3>Inventory Manager</h3>
                </div>
                <div class="col-md-12 col-lg-12 mb-2">
                    <div class="card">
                        <div class="card-header"><h4>Add Item</h4></div>
                        <div class="card-body">
                            <form action="./InventoryManager.php" method="post" id="inventory_form">
                                <div class="form-group">
                                    <label for="product_name">Product name:</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required="">
                                </div>
                                <div class="form-group">
                                    <label for="stock_qty">Quantity in stock:</label>
                                    <input type="text" class="form-control" name="stock_qty" id="stock_qty" required="">
                                </div>
                                <div class="form-group">
                                    <label for="item_price">Price per item:</label>
                                    <input type="text" class="form-control" id="item_price" name="item_price" required="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="save_btn" class="btn btn-success" >Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="row" id="data-display">
                <div class="table-responsive col-lg-12 col-md-12 mt-2">
                    <h3>Inventory Records</h3>
                    <table class="table">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product name</th>
                                <th scope="col">Quantity in stock</th>
                                <th scope="col">Price per item</th>
                                <th scope="col">Datetime submitted</th>
                                <th scope="col">Total value number</th>
                            </tr>
                        </thead>
                        <tbody id="inventory_data">
                            <tr>
                                <td colspan="6">No record entry found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>  
        </section>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script type="text/javascript">
          window.onload = function(){
            loadInventoryData($("#inventory_form").attr('action'))
            $("#inventory_form").on('submit',function(e){
              e.preventDefault();
              var formData = $(this).serialize();
              var serverUrl = $(this).attr('action');
              $.ajax({
                method: "POST",
                url: serverUrl,
                data: formData,
                dataType: 'json',
                success: function(data){
                    if(typeof data == "object"){
                        //show success notification
                        alert("Record saved");
                        readData(data.sort());
                    }
                    if(data.error){
                        //show error notification
                        alert(data.error);
                    }
                },
                error: function(e){
                  alert('Error: '+e.statusText);
                },
              });
            });
            
            function readData(data){
                var row = "";
                var sumTotalValue = 0;
                for(i =0;i < data.length;i++){
                    var date = new Date(data[i].date_submitted * 1000);
                    //console.logdate.toDateString(),date.toLocaleString())
                    row += '<tr>';
                    row += '<td>'+(i+1)+'</td>';
                    row += '<td>'+data[i].product_name+'</td>';
                    row += '<td>'+data[i].stock_qty+'</td>';
                    row += '<td>'+data[i].item_price+'</td>';
                    row += '<td>'+date.toLocaleString()+'</td>';
                    row += '<td>'+data[i].total_value+'</td>';
                    row += '</tr>';
                    sumTotalValue += data[i].total_value;
                }
                row += '<tr>';
                row += '<td colspan="5"><span class="text-bold">TOTAL VALUE NUMBERS</span></td>';
                row += '<td>'+sumTotalValue+'</td>';
                row += '</tr>';
                $('#inventory_data').html(row);
            }
            
            function sumTotalValueNumbers(currentTotalNum = 0,num = 0){
                return currentTotalNum + num;
            }
            
            function loadInventoryData(url){
                $.get(url,{get: "inventory"},function(data){
                    if(typeof data == "object"){
                        if(data.error){
                            //show error notification
                            alert(data.error);
                            return false;
                        }
                        readData(data.sort());
                    }
                },"json");
            }
          }
        </script>
    </body>
</html>