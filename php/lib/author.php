<?php
require_once dirname(__DIR__, 1) . "/classes/Author.php";

function bar() {
	$authorId = "7c0686ea-34ab-4ed3-b7bd-9cdb558a1a5e";
	$authorActivationToken = "2cdbd8fc2cdbd8fc2cdbd8fc2cdbd8fc";
	$authorAvatarUrl = "https:/avatars.right.org/doing/m";
	$authorHash = "3ce7418e3ce7418e3ce7418e3ce7418e";
	$authorEmail = "levi012@gmail.com";
	$authorUsername = "Levi";

echo $authorEmail;

	$author = new \CathyLaure\ObjectOriented\Author($authorId, $authorActivationToken, $authorAvatarUrl, $authorEmail,$authorHash, $authorUsername);
		var_dump($author);
	echo "$authorEmail <br> $authorActivationToken <br> $authorHash";
}
bar();
