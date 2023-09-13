$(document).ready( function (){
	$.post("timesheet/timesheet_submition_check", {  }, function(data, status)
	{
		if(data != '[]')
		{
			// CHANGE TO OBJECT
			timesheet_list = JSON.parse(data);
			// DATE INITIALIZE
			var date           = new Date();

			// TO SHOW TODAY DATE IN '2020-04-06' // WITHOUT .slice IT WILL JUST ONLY SHOW '2020-4-6'
			var today 		   = date.getFullYear()+'-'+("0" + (date.getMonth()+1)).slice(-2)+'-'+("0" + date.getDate()).slice(-2);

			var submition_date = new Date(third_working_date);

			// CHECK IS TODAY >= THIRD WORKING DAY
			if(today >= third_working_date){

				for($i=0 ; $i<timesheet_list.length ; $i++)
				{
					if(timesheet_list[$i]['content'] != "")
					{
						content = JSON.parse(timesheet_list[$i]['content']);
						var empty_row_leave = [];
						var empty_row_idle = [];
						var empty_row = [];
						var result = [];
						var holiday;

						$.ajax({
					        type: "POST",
					        'async' : false,
					        url:  "timesheet/get_holiday",
					        data: '&emp_id='+timesheet_list[$i]['employee_id'],
					        dataType: "json",
					        success: function(data){
					        	holiday = data;
					        }
					   	});

						content.forEach(function(row, index){
			          		var obj  = [];

			          		row.forEach(function(item, index){
			          			obj[hot.getColHeader()[index]]  = item;
			          			empty_row_leave[hot.getColHeader()[index]] ="";
			          			empty_row_idle[hot.getColHeader()[index]] ="";
			          			empty_row[hot.getColHeader()[index]] ="";
			          		});

			          		// result.push(obj);
			          		if(obj['Activities'] != "")
			          		{
			          			result.push(obj);
			          		}
			      		});

			      		if(this_month_leave != ''){
			      			this_month_leave2 = JSON.parse(this_month_leave);
			      		}
			      		else{
			      			this_month_leave2 = [];
			      		}
						
						var total_leave_hours = 0;

						for($n=0 ; $n<this_month_leave2.length ; $n++)
						{
							if(this_month_leave2[$n]['employee_id'] == timesheet_list[$i]['employee_id'])
							{
								var start = new Date(this_month_leave2[$n]['start_date']);
								var start_date = start.getDate();
								var start_time = this_month_leave2[$n]['start_time'];

								var end = new Date(this_month_leave2[$n]['end_date']);
								var end_date = end.getDate();
								var end_time = this_month_leave2[$n]['end_time'];

								empty_row_leave['Activities'] = 'On Leave';

								empty_row_leave.forEach(function(item, index){
									var date     = new Date(timesheet_list[$i]['month']);
	    							var date_day = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index);
									var leave_hours;

									if(date_day >= start && date_day <= end){
										if(index == start_date)
										{
											if(start_time == "1:00 PM"){
												leave_hours = set_string(4);
											}
											else if(end_time == "1:00 PM"){
												leave_hours = set_string(4);
											}
											else{
												leave_hours = set_string(8);
											}
											empty_row_leave[index] = leave_hours;
											total_leave_hours = set_string(parseFloat(total_leave_hours) + parseFloat(leave_hours));
										}
										else if(index == end_date)
										{
											if(start_time == "1:00 PM"){
												leave_hours = set_string(4);
											}
											else if(end_time == "1:00 PM"){
												leave_hours = set_string(4);
											}
											else{
												leave_hours = set_string(8);
											}
											empty_row_leave[index] = leave_hours;
											total_leave_hours = set_string(parseFloat(total_leave_hours) + parseFloat(leave_hours));
										}
										else
										{
											leave_hours = set_string(8);
											empty_row_leave[index] = leave_hours;
											total_leave_hours = set_string(parseFloat(total_leave_hours) + parseFloat(leave_hours));
										}
									}
									// else
									// {
									// 	empty_row_leave[index] = '-';
									// }
								});

								empty_row_leave['current'] = total_leave_hours;
								empty_row_leave['total'] = total_leave_hours;

								empty_row_leave.forEach(function(item, index){

									if(item == ""){
										empty_row_leave[index] = '-';
									}

								});
							}
						}

						if(empty_row_leave['Activities'] != "")
		          		{
		          			result.push(empty_row_leave);
		          		}

		          		if(result.length != '0')
		          		{
		          			var reverse_result = [];
					  		var tt_array = [];
					  		var array = [];

					  		empty_row_idle['Activities'] = 'Idle';

					  		for (var col = 1; col < result[0].length; col++) {
					  			tt_array = [];
						        for (var row = 0; row < result.length; row++) {
						        	array = result[row][col];
						        	tt_array.push(array);
						        }
						        reverse_result.push(tt_array);
					        }

					  		reverse_result.forEach(function(row, index){

					        	var total = '8.0';
					        	var is_blocked = true;

					      		row.forEach(function(item, index){
					      			if(item!="" && item!=null && item!='-')
					      			{
					      				total = set_string(parseFloat(total) - parseFloat(item));
					      				is_blocked = false;
					      			}
					      			else if(item==""){
					      				is_blocked = false;
					      			}
					      		});

					      		if(is_blocked)
					      		{
					      			empty_row_idle[index+1] = '0.0';
					      		}
					      		else
					      		{
					      			empty_row_idle[index+1] = total;
					      		}
					  		});

					        var idle_current_total = '0.0';

					        empty_row_idle.forEach(function(item, index){
					        	var date     = new Date(timesheet_list[$i]['month']);
		    					var date_day = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index);
		    					var date_day2 = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+index;

		    					if(date_day.getDay() == 0 || date_day.getDay() == 6){
		    						empty_row_idle[index] = '0.0';
		    					}

		    					if(holiday != ''){
		    						for(var h = 0 ; h < holiday.length ; h ++){
		    							var holiday_date = new Date(holiday[h]['holiday_date']);
		    							holiday_date2 = holiday_date.getFullYear()+'-'+(holiday_date.getMonth()+1)+'-'+holiday_date.getDate();

		    							if(holiday_date2 == date_day2){
		    								empty_row_idle[index] = '0.0';
		    							}
		    						}
		    					}

		    					idle_current_total = set_string(parseFloat(idle_current_total) + parseFloat(empty_row_idle[index]));
		    					
					        });

					        empty_row_idle['current'] = idle_current_total;
					        empty_row_idle['total'] = idle_current_total;

					    	result.push(empty_row_idle);

					    	// IF TIMESHEET ROW < 5, ADD EMPTY ROW (CAUSE HONDSONTABLE SET MINROWS : 5)
							if(result.length < 5){
								var position = 0; 
								var empty_row_needed = 5 - result.length;

								for($z=0;$z<result.length;$z++){

									if(result[$z]['Activities'] == 'On Leave' || result[$z]['Activities'] == 'Idle'){
										var position = $z;
										break;
									}
								}

								for($y=0;$y<empty_row_needed;$y++){
									result.splice(position,0,empty_row);
								}
							}

					    	hot.loadData(result);
					    	total_calculation();
					    	timesheet_list[$i]['content'] = hot.getData();

				      		$.post("timesheet/Timesheet_Submition", { 'timesheet_list' : timesheet_list[$i] }, function(data, status){
								if(data){
									location.reload();
								}
				        	});
		          		}
			      	}
				}
			}
		}
    });
});

function total_calculation(){
	var table1 = hot.getData();
	var result1 = [];
	var empty_row = [];

	table1.forEach(function(row, index){
  		var obj  = [];

  		row.forEach(function(item, index){
  			obj[hot.getColHeader()[index]]  = item;
  			empty_row[hot.getColHeader()[index]]  = '';
  		});

  		result1.push(obj);
		});

		var reverse_result = [];
		var tt_array = [];
		var array = [];

		for (var col = 1; col < result1[0].length; col++) {
			tt_array = [];
        for (var row = 0; row < result1.length; row++) {
        	array = result1[row][col];
        	tt_array.push(array);
        }
        reverse_result.push(tt_array);
    }
    var current_total = '0.0';
    reverse_result.forEach(function(row, index){
    	var total = '0.0';
  		row.forEach(function(item, index){
  			if(item!="" && item!=null && item!="-")
  			{
  				total = set_string(parseFloat(total) + parseFloat(item));
  			}
  		});
  		empty_row[index+1] = total;
  		current_total = set_string(parseFloat(current_total) + parseFloat(total));
		});
    empty_row['current'] = current_total;
    empty_row['Activities'] = 'Total';
    result1.push(empty_row);
    hot.loadData(result1);
}
