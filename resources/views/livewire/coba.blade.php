
<main id="main" class="main-site">
    <button id="pay-button">Pay!</button>
   

    <form id="payment-form" method="get">
      <input type="hidden" name="result_data" id="result-data" value="">
    </form>
    


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
