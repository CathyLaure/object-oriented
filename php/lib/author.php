<?php
require_once dirname(__DIR__, 1) . "/classes/Author.php";

function bar() {
	$authorId = "2261aff8-6a23-11ea-bc55-0242ac130003";
	$authorActivationToken = "b50e0510-6a26-11ea-bc55-0242ac130003";
	$authorAvatarUrl = "https:/avatars.right.org/doing/m";
	$authorHash = "861d7320-6a27-11ea-bc55-0242ac130003";
	$authorEmail = "levi012@gmail.com";
	$authorUsername = "Levi";

echo $authorEmail;

	$author = new \CathyLaure\ObjectOriented\Author($authorId, $authorActivationToken, $authorAvatarUrl, $authorHash, $authorEmail, $authorUsername);
		var_dump($author);
	echo "$authorEmail <br> $authorActivationToken <br> $authorHash";
}
bar();
