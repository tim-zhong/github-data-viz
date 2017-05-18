<?php

function listrepos(){
	$repos = apiRequest("https://api.github.com/user/repos");
	foreach ($repos as $r) {
		?>
		<!-- <pre>
			<?php print_r($r); ?>
		</pre> -->
		<div class="repo-card card">
			<h5 class="card-title">
				<?php echo $r->name; ?>
			</h5>
			<p class="card-text">
				<?php echo $r->description; ?>
			</p>
			<footer>
	        	<small class="text-muted">
	          		<?php echo $today = date("F j, Y, g:i a", strtotime($r->created_at)); ?>
	        	</small>
	      	</footer>
		</div>
		<?php
	}
}