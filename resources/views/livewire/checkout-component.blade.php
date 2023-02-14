
<main id="main" class="main-site">
<style>
.summary-item .row-in-form input[type=password], .summary-item .row-in-form input[type=text], .summary-item .row-in-form input[type=number] {
    font-size: 13px;
    line-height: 19px;
    display: inline-block;
    height: 43px;
    padding: 2px 20px;
    width: 100%;
    border: 1px solid #e6e6e6;
}

</style>
<div class="container">

    <div class="wrap-breadcrumb">
        <ul>
            <li class="item-link"><a href="/" class="link">home</a></li>
            <li class="item-link"><span>Checkout</span></li>
        </ul>
    </div>
    <div class=" main-content-area">
        
            <div class="summary summary-checkout">
                <div class="summary-item payment-method">
                    
                    @if(Session::has('checkout'))
                        <p class="summary-info grand-total"><span>Grand Total</span> <span class="grand-total-price">@currency(Session::get('checkout')['total'])</span></p>
                    @endif

                    


                    <button id="pay-button" type="submit" class="btn btn-medium">Place order now</button>
                </div>
                
            </div>
        
        
        <h3>{{$result_pay}}</h3>
        <form id="payment-form" method="get">
            <input type="hidden" name="result_data" id="result-data" value="">
        </form>
        
    </div>
</div>

<script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        //SnapToken acquired from previous step

        var resultType = document.getElementById('result-type');
        var resultData = document.getElementById('result-data');
        function changeResult(type, data)
        {
          $("#result-type").val(type);
          $("#result-data").val(JSON.stringify(data));
          //resultType.innerHTML = type;
          //resultData.innerHTML = JSON.stringify(data);
        }
        snap.pay('{{$snapToken}}', {
          onSuccess: function(result){
            changeResult('success', result);
            console.log(result.status_message);
            console.log(result);
            $("#payment-form").submit();
          },
          onPending: function(result){
            changeResult('pending', result);
            console.log(result.status_message);
            $("#payment-form").submit();
          },
          onError: function(result){
            changeResult('error', result);
            console.log(result.status_message);
            $("#payment-form").submit();
          }
        });
      };
    </script>
</main>
