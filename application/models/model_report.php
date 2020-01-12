<?
class Model_Report extends Model{
	
	public function get_data($period = ''){
		if(in_array($period, array('day', 'week', 'month'))){
			$ways = Model_Ways::get_data();
			$date_start = date("Y-m-d", strtotime("-1 ".$period))." 00:00:00";
			$date_end = date("Y-m-d")." 23:59:59";
			foreach($ways as $key=>$way){
				$query = "select t1.id, t1.way_id, t1.car_id, t1.time_start, t1.time_end, t1.weight, t1.traffic_type,
							c1.name as car_name, c1.reg_mark, c1.fuel_cons,
							sum(t2.mileage) as sum_mil
							from traffic t1
							inner join cars c1 on c1.id = t1.car_id
							inner join traffic_points t2 on t2.traffic_id = t1.id
							where t1.way_id = :way_id and t1.time_end is not NULL
							and t1.time_start >= :time_start and t1.time_end <= :time_end
							group by t1.id";
				$ways[$key]['fact_fuel'] = 0;
				$ways[$key]['fact_cargo'] = 0;
				
				$params = array('way_id'=>$way['id'], 'time_start'=>$date_start, 'time_end'=>$date_end);
				$traffics = db::sql_select($query, $params);
				
				$query2 = "select t1.id as id1, t1.way_id, t2.*
							from traffic t1
							inner join traffic_points t2 on t2.traffic_id = t1.id
							where t1.way_id = :way_id and t1.time_end is not NULL
							and t1.time_start >= :time_start and t1.time_end <= :time_end
							";
				$params2 = array('way_id'=>$way['id'], 'time_start'=>$date_start, 'time_end'=>$date_end);
				$traffic_points = db::sql_select($query2, $params2);
				$traffic_points2 = array();
				foreach($traffic_points as $traffic_point){
					$traffic_points2[$traffic_point['traffic_id']][$traffic_point['id']] = $traffic_point;
				}
								
				foreach($traffics as $traffic){
					$ways[$key]['fact_fuel'] += $traffic['sum_mil']*$traffic['fuel_cons']/100;
					$ways[$key]['fact_cargo'] += $traffic['weight'];
				}
				
				$ways[$key]['traffics'] = $traffics;
				$ways[$key]['traffic_points'] = $traffic_points2;
			}
			return $ways;
		}
	}
	
}
