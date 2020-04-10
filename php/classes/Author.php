<?php
namespace CathyLaure\ObjectOriented;

require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Author implements \JsonSerializable {
	use ValidateUuid;

	//authorId binary(16) not null,
//authorActivationToken char(32),
//authorAvatarUrl varchar(255),
//authorEmail varchar(128) not null,
//authorHash char(97) not null,
//authorUsername varchar(32) not null,

	/*
	 * id for this primary key
 	* @var Uuid authorId
 	*/
	private $authorId;

	/*
	 * activation token for this author
	 */
	private $authorActivationToken;

	/*
	 * avatar url for this Author
	 */
	private $authorAvatarUrl;

	/*
	 * email for this Author
	 * @var string $authorEmail
	 */
	private $authorEmail;

	/*
	 * hash for this Author
	 * @var string $authorHash
	 */
	private $authorHash;

	/*
	 * username for this Author
	 * @var string $authorUsername
	 */
	private $authorUsername;


	/*
 * Constructor for this Author
 * @param string|Uuid $authorId id of Author or null if a new id
 * @param string $authorActivationToken activation token to safe gaurd against malicious code
 * @param string $authorAvatarUrl string containing an avatar url or null
 * @param string $authorEmail string containing the email
 * @param string $authorHash string containing the password hash
 * @param string $authorUsername string containing username
 * @throws \InvalidArgumentException if data typs are not valid
 * @throws \RangeException if data values are out of bound (eg , strings too long)
 * @throws \TypeError if data types voilates data hints
 * @throws \ Exception if some other exception uccurs
 * @Documentation https://php.net/manual/en/language.oop5.decon.php
 */
	public function __construct($newAuthorId, ?string $newAuthorActivationToken, string $newAuthorAvatarUrl, $newAuthorEmail, string $newAuthorHash, string $newAuthorUserName) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUserName($newAuthorUserName);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id
	 **/
	public function getAuthorId(): Uuid {
		return ($this->authorId);
	}

	/**
	 * mutator method for author id
	 *
	 * @param Uuid|string $newAuthorId new value of author id
	 * @throws \RangeException if $newAuthorId is not positive
	 * @throws \TypeError if $newAuthorId is not a uuid or string
	 **/
	public function setAuthorId($newAuthorId): void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		$this->authorId = $uuid;
	}


	/**
	 * accessor method for activation token
	 *
	 * @return string value of activation token
	 **/
	public function getAuthorActivationToken(): string {
		return ($this->authorActivationToken);
	}

	/**
	 * mutator method for author activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setAuthorActivationToken(string $newAuthorActivationToken): void {
		if($newAuthorActivationToken === null) {
			$this->authorActivationToken = null;
			return;
		}
		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false) {
			throw(new\RangeException("author activation is not valid"));
		}
		//make sure the author activation token is only 32 characters
		if(strlen($newAuthorActivationToken) !== 32) {
			throw(new\RangeException("author activation has to be 32"));
		}
		$this->authorActivationToken = $newAuthorActivationToken;
	}

	/**
	 * accessor method for author Avatar Url
	 *
	 * @return string value of avatar url
	 **/

	public function getAuthorAvatarUrl(): string {
		return ($this->authorAvatarUrl);
	}

	/**
	 * mutator method for author avatar url
	 *
	 * @param string $newAuthorAvatarUrl new value of author avatar URL
	 * @throws \InvalidArgumentException if $newAuthorAvatarUrl is not a string or insecure
	 * @throws \RangeException if $newAuthorAvatarUrl is > 255 characters
	 * @throws \TypeError if $newAuthorAvatarUrl is not a string
	 **/
	public function setAuthorAvatarUrl(string $newAuthorAvatarUrl): void {

		$newAuthorAvatarUrl = trim($newAuthorAvatarUrl);
		$newAuthorAvatarUrl = filter_var($newAuthorAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		// verify the avatar URL will fit in the database
		if(strlen($newAuthorAvatarUrl) > 255) {
			throw(new \RangeException("image cloudinary content too large"));
		}
		// store the image cloudinary content
		$this->AuthorAvatarUrl = $newAuthorAvatarUrl;
	}

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getAuthorEmail(): string {
		return $this->authorEmail;
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newAuthorEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setAuthorEmail($newAuthorEmail): void {

		// verify the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("author email is empty or insecure"));
		}

		// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 128) {
			throw(new \RangeException("author email is too large"));
		}

		// store the email
		$this->authorEmail = $newAuthorEmail;
	}

	/**
	 * accessor method for authorHash
	 *
	 * @return string value of hash
	 */

	public function getAuthorHash(): string {
		return $this->authorHash;
	}

	/**
	 * mutator method for author hash password
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if author hash is not a string
	 */
	public function setAuthorHash(string $newAuthorHash): void {
		//enforce that the hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("author password hash empty or insecure"));
		}

		//enforce the hash is really an Argon hash
	//	$authorHashInfo = password_get_info($newAuthorHash);
	//	if($authorHashInfo["algoName"] !== "argon2i") {
	//		throw(new \InvalidArgumentException("author hash is not a valid hash"));
	//	}

		//enforce that the hash is exactly 128 characters.


		//store the hash
		$this->authorHash = $newAuthorHash;
	}

	/**
	 * accessor method for username
	 *
	 * @return string value of username or null
	 **/
	public function getAuthorUsername(): ?string {
		return ($this->authorUsername);
	}

	/**
	 * mutator method for username
	 *
	 * @param string $newAuthorUsername
	 * @throws \InvalidArgumentException if $newUsername is not a string or insecure
	 * @throws \RangeException if $newUsername is > 32 characters
	 * @throws \TypeError if $newUsername is not a string
	 **/
	public function setAuthorUsername(?string $newAuthorUsername): void {
		//if $authorUsername is null return it right away
		if($newAuthorUsername === null) {
			$this->authorUsername = null;
			return;
		}


		// verify the username is secure
		$newAuthorUsername = trim($newAuthorUsername);
		$newAuthorUsername = filter_var($newAuthorUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAuthorUsername) === true) {
			throw(new \InvalidArgumentException("author username is empty or insecure"));
		}

		// verify the username will fit in the database
		if(strlen($newAuthorUsername) > 32) {
			throw(new \RangeException("author username is too large"));
		}

		// store the phone
		$this->authorUsername = $newAuthorUsername;
	}



/*
 * inserts this Author into mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/
public function insert(\PDO $pdo): void {


	// create query template
	$query = "INSERT INTO author(AuthorId, authorActivationToken, authorAvatarUrl,  authorEmail, authorHash, authorUsername) VALUES (:authorId, :authorActivationToken, :authorAvatarUrl, :authorEmail, :authorHash, :authorUsername)";
	$statement = $pdo->prepare($query);

	$parameters = ["authorId" => $this->authorId->getBytes(), "authorActivationToken" => $this->authorActivationToken, "authorAvatarUrl" => $this->authorAvatarUrl, "authorEmail" => $this->authorEmail, "authorHash" => $this->authorHash,"authorUsername" => $this->authorUsername];
	$statement->execute($parameters);


}

/**
 * deletes this Author from mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/
public function delete(\PDO $pdo): void {

	// create query template
	$query = "DELETE FROM author WHERE authorId = :authorId";
	$statement = $pdo->prepare($query);

	//bind the member variables to the place holders in the template
	$parameters = ["authorId" => $this->authorId->getBytes()];
	$statement->execute($parameters);
}

/**
 * updates this Author from mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 **/
public function update(\PDO $pdo): void {


	// create query template
	$query = "UPDATE author SET authorActivationToken = :authorActivationToken, authorAvatarUrl = :authorAvatarUrl, authorEmail = :authorEmail, authorHash = :authorHash, authorUsername = :authorUsername WHERE authorId = :authorId";
	$statement = $pdo->prepare($query);

	// bind the member variables to the place holders in the template

	$parameters = ["authorId" => $this->authorId->getBytes(), "authorActivationToken" => $this->authorActivationToken, "authorAvatarUrl" => $this->authorAvatarUrl, "authorEmail" => $this->authorEmail, "authorHash" => $this->authorHash, "authorUsername" => $this->authorUsername];
	$statement->execute($parameters);
}

	/**
	 * gets a single data from the Author table in mySQL
	 * @param uuid|string $authorId author id to search for
	 * @param \PDO $pdo PDO connection object
	 * @return Author|null Author found or null of not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws TypeError if $pdo is not a PDO connection object
	 **/
	public function getAuthorByAuthorId(\PDO $pdo, $authorId): ?Author {
		//create query template
		$query = "SELECT authorId,
		authorActivationToken,
		authorAvatarUrl,
		authorEmail,
		authorHash,
		authorUsername 
		FROM author WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);
		try {
			$authorId = self::validateUuid($authorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//bind the objects to their respective placeholders in the table
		$parameters = ["authorId" => $authorId->getBytes()];
		$statement->execute($parameters);

		//grab author from database

		$author = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false){
			//instantiate author object and push data into it
			$author = new Author($row["authorId"],
				$row["authorActivationToken"],
				$row["authorAvatarUrl"],
				$row["authorEmail"],
				$row["authorHash"],
				$row["authorUsername"]);
		}
		return ($author);


	}

/**
 * gets the Author by email
 *
 * @param \PDO $pdo PDO connection object
 * @param string $authorEmail email to search for
 * @return Author|null Author or null if not found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not the correct data type
 **/
public static function getAuthorByAuthorEmail(\PDO $pdo, string $authorEmail): ?Author {
	// sanitize the email before searching
	$authorEmail = trim($authorEmail);
	$authorEmail = filter_var($authorEmail, FILTER_VALIDATE_EMAIL);

	if(empty($authorEmail) === true) {
		throw(new \PDOException("not a valid email"));
	}

	// create query template
	$query = "SELECT authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername FROM author WHERE authorEmail = :authorEmail";
	$statement = $pdo->prepare($query);

	// bind the author id to the place holder in the template
	$parameters = ["authorEmail" => $authorEmail];
	$statement->execute($parameters);

	// grab the Author from mySQL
	try {
		$author = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$author = new Author($row["authorId"], $row["authorActivationToken"], $row["authorAvatarUrl"], $row["authorEmail"], $row["authorHash"], $row["authorUsername"]);
		}
	} catch(\Exception $exception) {
		// if the row couldn't be converted, rethrow it
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return ($author);
}

/**
 * get the author by author activation token
 *
 * @param string $authorActivationToken
 * @param \PDO object $pdo
 * @return Author|null Author or null if not found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not the correct data type
 **/
public static function getAuthorByAuthorActivationToken(\PDO $pdo, string $authorActivationToken) : ?Author {
	//make sure activation token is in the right format and that it is a string representation of a hexadecimal
	$profileActivationToken = trim($authorActivationToken);
	if(ctype_xdigit($authorActivationToken) === false) {
		throw(new \InvalidArgumentException("author activation token is empty or in the wrong format"));
	}

	//create the query template
	$query = "SELECT  authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername FROM author WHERE authorActivationToken = :authorActivationToken";
	$statement = $pdo->prepare($query);

	// bind the author activation token to the placeholder in the template
	$parameters = ["authorActivationToken" => $authorActivationToken];
	$statement->execute($parameters);

	// grab the Author from mySQL
	try {
		$author = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();
		if($row !== false) {
			$author = new Author($row["authorId"], $row["authorActivationToken"], $row["authorAvatarUrl"], $row["authorEmail"], $row["authorHash"], $row["authorUsername"]);
		}
	} catch(\Exception $exception) {
		// if the row couldn't be converted, rethrow it
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return ($author);
}

	/**
	 * pulls User names fromAuthor data from mySQL
	 * @param string $authorUsername
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Authors found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws TypeError if $pdo is not a PDO connection object
	 **/

	public function getAuthorByUsername(\PDO $pdo, string $authorUsername) : \SplFixedArray {
		// create query template
		$query= "SELECT authorId, 
					authorActivationToken, 
					authorAvatarUrl, authorEmail, authorHash, 
					authorUsername FROM author 
					WHERE authorUsername LIKE :authorUsername";
		$statement = $pdo->prepare($query);


		//bind the author username to the placeholder in the template
		$authorUsername = "%$authorUsername%"; //searches for any character similar either from the left or right
		$parameters = ["authorUsername"=>$authorUsername];
		$statement->execute($parameters);

		// build an array of author
		$authors = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$author = new Author($row["authorId"],
					$row["authorActivationToken"],
					$row["authorAvatarUrl"],$row["authorEmail"],
					$row["authorHash"],
					$row["authorUsername"]);
				//To know the length of an array when you have no clue what's in it
				$authors[$authors->key()] = $author;
				$authors->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return ($authors);

		//Fetch all authors from database
		//$row = $statement->fetchAll();

		//returned Array of author
		//return $row;

	}


/**
 * formats the state variables for JSON serialization
 *
 * @return array resulting state variables to serialize
 **/
public function jsonSerialize(): array {
	$fields = get_object_vars($this);

	$fields["authorId"] = $this->authorId->toString();
	$fields["authorActivationToken"] = $this->authorActivationToken->toString();
	$fields["authorAvatarUrl"] = $this->authorAvatarUrl->toString();
	$fields["authorEmail"] = $this->authorEmail->toString();
	$fields["authorHash"] = $this->authorHash->toString();
	$fields["authorUsername"] = $this->authorUserName->toString();

	return ($fields);
}
}