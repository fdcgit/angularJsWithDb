<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap/3.3.7/css/bootstrap.min.css">
	<title>Hotel Order Page</title>
	<style type="text/css">
		
		#idOrderInputContainer {
			margin: 5%;
		}

		#idValidateColor {
			color: red;
		}

	</style>
</head>
<body ng-app="mainApplication" ng-init="total_amount = 0;">
	<div class="container-fluid" ng-controller="mainController">
	    
		<h1 align="center">Place Your Order:</h1>

		<div id="idOrderInputContainer">
			<form method="post" class="form-group" name="formTag">
				<label>Item Description</label>
				<input type="text" name="order_desc" class="form-control" ng-model="desc" required/><br>

				<span id="idValidateColor" ng-show="formTag.order_desc.$error.required">Please fill the field</span><br><br>

				<label>Item Quantity:</label>
				<input type="number" name="order_qty" class="form-control" ng-model="qty" required/><br>

				<span id="idValidateColor" ng-show="formTag.order_qty.$error.number">Please fill the numeric data</span><br><br>

				<label>Item Amount:</label>
				<input type="number" name="order_amt" class="form-control" ng-model="amt" required/><br>

				<span id="idValidateColor" ng-show="formTag.order_amt.$error.number">Please fill the numeric data</span><br><br>

				<button class="btn btn-success" ng-click="compute()">Show Final Total</button><br><br>

				<h4 id="idAmountResult">{{total_amount}}</h4><br>

				<input type="submit" name="insert_record" value="Confirm Order" class="btn btn-primary" ng-show = "showConfirmBtn" ng-click="insertInfoToDb()" />

                <button class="btn btn-info" ng-click="showDataFromDb();">View</button>

			</form>

		</div>


		<table class="table table-bordered">
			<tr ng-repeat="x in viewDbData">
				<td>{{x.order_id}}</td>
				<td>{{x.order_desc}}</td>
				<td>{{x.order_qty}}</td>
				<td>{{x.order_amt}}</td>
			</tr>
		</table>

	</div>

<script src="angularjs/angular.min.js"></script>
<script type="text/javascript">
	
	var mainApplication = angular.module("mainApplication", []);

	mainApplication.factory('mainFactory', function() {

		var computeAmount = {};

		computeAmount.calculate = function(amount, quantity, discount) {

			var discountRateCompute = ((amount*quantity) * (discount/100));

			var amountTotal = amount * quantity;

			return amountTotal - discountRateCompute;
		}

		return computeAmount;

	});


	mainApplication.service('mainService', function(mainFactory) {

		this.calculate = function(amount, quantity, discount) {
			return mainFactory.calculate(amount, quantity, discount);	
		}
		
	});


	mainApplication.controller("mainController", function($scope, mainService, $http) {
		$scope.compute = function() {
			
			$scope.showConfirmBtn = false;

			if($scope.desc == null || $scope.qty == null || $scope.amt == null) {
				$scope.showConfirmBtn = false;
				document.getElementById("idAmountResult").innerHTML = "0";
			} else {
				$scope.showConfirmBtn = true;
			}

			$http.get(
				"http://www.ftpl.org/hotel/database/select_discount_rate.php"
			).then(function(response) {
			    
			    objResult = response.data.map(discount => discount.discount_rate);
			    
			    $scope.discount = parseInt(objResult);

				alert($scope.discount);

    			$scope.total_amount = mainService.calculate($scope.amt, $scope.qty, parseInt(objResult));
			}, function(error) {

			});


		}

		$scope.insertInfoToDb = function() {
		    console.log("Clicked for insertion");
		    
		    if($scope.desc == null || $scope.desc == "" && $scope.qty == null || $scope.qty == "" && $scope.total_amount == null || $scope.total_amount == "" && $scope.amt == null || $scope.amt == "") {
		        return;
		    } else {
		    
    			$http.post(
    				"http://www.ftpl.org/hotel/database/insert_data_db.php", {
    					'order_desc': $scope.desc,
    					'order_qty' : $scope.qty,
    					'order_amt' : $scope.total_amount
    				}
    
    			).then(function (response){
    				alert("Data Inserted To Database");
    				$scope.desc = "";
    				$scope.qty = "";
    				$scope.amt = "";
    				$scope.total_amount = "";
               },function (error){
            
               });
            }
		}

		$scope.showDataFromDb = function() {
			console.log("Clicked to View Db Records");

			$http.get(
				"http://www.ftpl.org/hotel/database/select_data_db.php"
			).then(function(response) {

				$scope.viewDbData = response.data;
			}, function (error) {

			});
		}


	});


</script>
</body>
</html>