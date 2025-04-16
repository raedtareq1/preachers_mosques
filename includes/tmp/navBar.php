<!-- Grey with black text -->
<div class=" bg-dark">
	<nav class="container nav navbar navbar-expand-sm">
		<a class="navbar-brand text-white" href="index.php">الرئيسية</a>
		<ul class="navbar-nav pull-left">
			<li class="nav-item">
				<a class="nav-link text-white" href="preachers.php">الخطباء</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="mosq.php">المساجد</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="random-table.php">انشاء جدول تلقائي</a>
			</li>
		</ul>
			<?php 
				if (isset($_SESSION['userPreacher'])) {
					echo "<div class='me-auto d-flex'>";
						echo '<li class="nav-item me-auto">';
						echo '<div title="Online" class="bg-success rounded-circle border border-2 border-white position-absolute p-1" style="top: 25px;"></div>';
							echo '<a class="nav-link text-white" href="profile.php">' . $_SESSION["userPreacher"] . '</a>';
						echo '</li>';
						echo '<li class="nav-item ">';
							echo '<a class="nav-link text-warning" href="logout.php">خروج</a>';
						echo '</li>';
					echo "</div>";
				}
			?>
	</nav>
</div>