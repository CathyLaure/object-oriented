<?php
namespace CathyLaure\ObjectOriented\Test;

use CathyLaure\ObjectOriented\{Author};

//Hack!!! - added so this class could see DataDesignTest
require_once(dirname( __DIR__) . "/Test/DataDesignTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

class AuthorTest extends DataDesignTest {


private $Valid_Activation_Token;
private $Valid_Avatar_Url = "https:/avatars.right.org/doing/m";
private $Valid_Author_Email= "ctasama@cnm.edu";
private $Valid_Author_Hash; //this will be done in the setup.
private $Valid_Username = "ctasama";

	public function setUp(): void {
		parent::setUp();

		$password = "my secret password";
		$this->Valid_Author_Hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" =>45]);
		$this->Valid_Activation_Token = bin2hex(random_bytes(16));
	}

	public function testInsertValidAuthor() : void {
		//get count of author records in the database before we run the test.
		$numRows = $this->getConnection()->getRowCount("author");

		//insert an author record in the db
		$authorId = generateUuidV4()->toString();
		$author = new Author($authorId, $this->Valid_Activation_Token, $this->Valid_Avatar_Url, $this->Valid_Author_Email, $this->Valid_Author_Hash, $this->Valid_Username);
		$author->insert($this->getPDO());

		// check count of author records in the db after the insert
		$numRowsAfterInsert = $this->getConnection()->getRowCount("author");
		self::assertEquals($numRows + 1, $numRowsAfterInsert, "insert checked record count");

		//get a copy of the record just inserted and validate the values
		// make sure the values that went into the record are the same ones that come out.
		$pdoAuthor = Author::getAuthorByAuthorId($this->getPDO(), $author->getAuthorId()->toString());
		self::assertEquals($authorId, $pdoAuthor->getAuthorId());
		self::assertEquals($this->Valid_Activation_Token, $pdoAuthor->getAuthorActivationToken());
		self::assertEquals($this->Valid_Avatar_Url, $pdoAuthor->getAuthorAvatarUrl());
		self::assertEquals($this->Valid_Author_Email, $pdoAuthor->getAuthorEmail());
		self::assertEquals($this->Valid_Author_Hash, $pdoAuthor->getAuthorHash());
		self::assertEquals($this->Valid_Username, $pdoAuthor->getAuthorUsername());
	}


	public function testUpdateValidAuthor() : void {
		//get count of author records in the database before we run the test.
		$numRows = $this->getConnection()->getRowCount("author");

		//insert an author record in the db
		$authorId = generateUuidV4()->toString();
		$author = new Author($authorId, $this->Valid_Activation_Token, $this->Valid_Avatar_Url, $this->Valid_Author_Email, $this->Valid_Author_Hash, $this->Valid_Username);
		$author->insert($this->getPDO());

		//update a value on the record I just inserted.
		$changedAuthorUsername = $this->Valid_Username . "changed";
		$author->setAuthorUsername($changedAuthorUsername);
		$author->update($this->getPDO());

		// check count of author records in the db after the insert
		$numRowsAfterInsert = $this->getConnection()->getRowCount("author");
		self::assertEquals($numRows + 1, $numRowsAfterInsert, "insert checked record count");

		//get a copy of the record just inserted and validate the values
		// make sure the values that went into the record are the same ones that come out.
		$pdoAuthor = Author::getAuthorByAuthorId($this->getPDO(), $author->getAuthorId()->toString());
		self::assertEquals($authorId, $pdoAuthor->getAuthorId());
		self::assertEquals($this->Valid_Activation_Token, $pdoAuthor->getAuthorActivationToken());
		self::assertEquals($this->Valid_Avatar_Url, $pdoAuthor->getAuthorAvatarUrl());
		self::assertEquals($this->Valid_Author_Email, $pdoAuthor->getAuthorEmail());
		self::assertEquals($this->Valid_Author_Hash, $pdoAuthor->getAuthorHash());
		self::assertNotEquals($this->Valid_Username, $pdoAuthor->getAuthorUsername());
		//verify that the saved username is same as the updated username
		self::assertEquals($changedAuthorUsername, $pdoAuthor->getAuthorUsername());
	}



	public function testDeleteValidAuthor() : void {
		//get count of author records in the database before we run the test.
		$numRows = $this->getConnection()->getRowCount("author");

		//insert an author record in the db
		$authorId = generateUuidV4()->toString();
		$author = new Author($authorId, $this->Valid_Activation_Token, $this->Valid_Avatar_Url, $this->Valid_Author_Email, $this->Valid_Author_Hash, $this->Valid_Username);
		$author->insert($this->getPDO());

		// check count of author records in the db after the insert
		$numRowsAfterInsert = $this->getConnection()->getRowCount("author");
		self::assertEquals($numRows + 1, $numRowsAfterInsert, "insert checked record count");

		//now delete the record we just  inserted
		$author->delete($this->getPDO());

		//try to get the record. it should not exist.
		$pdoAuthor = Author::getAuthorByAuthorId($this->getPDO(), $author->getAuthorId()->toString());
		self::assertNull($pdoAuthor);

		//verify the record count has returned to the original count before the record was inserted.
		self::assertEquals($this->getConnection()->getRowCount("author"), $numRows);
	}

	public function testGetValidAuthorByAuthorId() : void {
		//how many records were in the db before we start?
		$numRows = $this->getConnection()->getRowCount("author");

		//now insert a row of data
		$authorId = generateUuidV4()->toString();
		$author = new Author($authorId, $this->Valid_Activation_Token, $this->Valid_Avatar_Url, $this->Valid_Author_Email, $this->Valid_Author_Hash, $this->Valid_Username);
		$author->insert($this->getPDO());

		//validate new row count in the table - should be old row count + 1 if insert is successful
		$numRowsAfterInsert = $this->getConnection()->getRowCount("author");
		self::assertEquals($numRows + 1, $numRowsAfterInsert);

		//now get the row we just inserted and verify that the data coming out of the db matches the data we put in the db
		$pdoAuthor = Author::getAuthorByAuthorId($this->getPDO(), $author->getAuthorId()->toString());
		self::assertEquals($pdoAuthor->getAuthorId(), $authorId);
		self::assertEquals($pdoAuthor->getAuthorActivationToken(), $this->Valid_Activation_Token);
		self::assertEquals($pdoAuthor->getAuthorAvatarUrl(), $this->Valid_Avatar_Url);
		self::assertEquals($pdoAuthor->getAuthorEmail(), $this->Valid_Author_Email);
		self::assertEquals($pdoAuthor->getAuthorHash(), $this->Valid_Author_Hash);
		self::assertEquals($pdoAuthor->getAuthorUsername(), $this->Valid_Username);
	}

	public function testGetValidAuthors() : void {
		//how many records were in the db before we start?
		$numRows = $this->getConnection()->getRowCount("author");
		$rowsInserted = 5;

		//now insert 5 rows of data
		for ($i=0; $i<$rowsInserted; $i++){
			$authorId = generateUuidV4()->toString();
			$author = new Author($authorId, $this->Valid_Activation_Token,
				$this->Valid_Avatar_Url,
				$this->Valid_Author_Email . $i,
				$this->Valid_Author_Hash,
				$this->Valid_Username . $i);
			$author->insert($this->getPDO());
		}

		//validate new row count in the table - should be old row count + 1 if insert is successful
		self::assertEquals($numRows + $rowsInserted, $this->getConnection()->getRowCount("author"));

		//validate number of rows coming back from our function.
		self::assertEquals($numRows + $rowsInserted, $author->getAllAuthors($this->getPDO())->count());
	}


}

