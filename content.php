<?php session_start(); if( ! empty($_SESSION['user'])) { 

require 'functions/functions.php';

?>
<!DOCTYPE html>
<html>
<head>

	<title>Twittersch</title>

	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/ionicons.min.css" />

</head>
<body>	

<div class="container">

	<?php require 'navigation.php'; ?>

	<div class="row-fluid tweet-form-wrap">
		
		<div class="span4"></div>

		<div class="span4">
			
			<h1><?php foreach(getCurrentUser() as $user){print $user['username'];} ?></h1>

			<form action="content.php" method="POST">
				<textarea name="content" cols="30" rows="5"></textarea><br>
				<input type="submit" value="skicke ut">
			</form>

			<div class="errors">
				<?php
					foreach($errors as $error) {
						print $error;
					}
				?>
			</div>

		</div>
		
		<div class="span4"></div>

	</div>

	<div class="row-fluid">
			
		<div class="span4"></div>
		
		<div class="span4 posts-wrap">

			<?php $posts = getPostsFromDB(($start -1) * $view); foreach ($posts as $post) : ?>
			<div class="post">
				
				<?php

					$conversationId = $post['conversation_id'];
					$replyId  	   = $post['post_id']; 
					$username 	   = $post['username'];
					$answerToNames = $post['answer_to_name'];

					print "<a href='profile.php?user=$username'>" . $username . '</a><br>';
					if($answerToNames != '') {
						print "<a href='profile.php?user=$answerToNames'>" . '@' . $answerToNames . '</a> ' . linkToAnchor($post['content']) . '<br><br>';
					} else {
						print linkToAnchor($post['content']) . '<br><br>';
					}
			
					foreach(getReplayPostsFromDB($post['post_id']) as $replyPost) : ?>
					<div class="reply_post">
						
						<?php

							$replyToId 	  = $replyPost['post_id'];
							$conversId    = $replyPost['conversation_id'];
							$replyName 	  = $replyPost['username'];
							$answerToName = $replyPost['answer_to_name'];

							if($replyPost['answer_to_id'] != 0) {	
								print  $replyPost['username'] . '<br>';
								print  "<a href='profile.php?user=$answerToName'>" . '@' . $answerToName . '</a>: ' . linkToAnchor($replyPost['content']);
					

						?>
						
						<form action="content.php" method="POST">
							<input type="text" name="reply">
							<input type="hidden" name="conversation_id" value="<?= $conversId ?>">
						 	<input type="hidden" name="answer_to_name" value="<?= $replyName ?>">
							<input type="hidden" name="reply_id" value="<?= $replyToId ?>">
							<input type="submit" value="reply">
						</form><br>	

					</div>
					<?php } endforeach; ?>

				<form action="content.php" method="POST">
					<input type="text" name="reply">
					<input type="hidden" name="conversation_id" value="<?php if($conversationId == 0){ print $replyId; } else { print $conversationId; } ?>">
					<input type="hidden" name="answer_to_name" value="<?= $username ?>">
					<input type="hidden" name="reply_id" value="<?= $replyId ?>">
					<input type="submit" value="reply">
				</form><br>

			</div>			

				<?php endforeach; ?>

			<div class="pagecount">

				<?php

					printPageLinks($pages, $start, '');	
		    	
			    ?>

		    </div>

		</div>

		<div class="span4"></div>

	</div>

</div>

</body>
</html>	

<?php

	} else {	
		header('Location: index.php');
	}
?>
