<?php
	if (have_posts()) {
		while (have_posts()) {
			the_post();

			$start =  date('Y-m-d',strtotime(get_post_meta(get_the_ID(), 'datacalendarioinicial', true)));
			$end =  date('Y-m-d',strtotime(get_post_meta(get_the_ID(), 'datacalendariofinal', true)));

			if (empty($end)) {
				$end = $start;
			}
			
			$jsonposts[] = array(
								'id' => get_the_ID(),
								'title' => get_the_title(),
								'start' => $start,
								'end' => $end,
								'url' => get_permalink()
			);
		}
	} 

	wp_reset_query();
	
	echo json_encode($jsonposts);