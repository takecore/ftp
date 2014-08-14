<?php

/**
 * Ftp
 *
 * @version 1.0
 * @author  Takehiro Ohtani
 * @license MIT License
 * @link    https://github.com/takecore
 */

class Ftp
{
	public $host;
	public $user_name;
	public $password;
	public $port = 21;
	public $timeout = 500;
	public $connection;

	/**
	 * FTP接続をオープンする
	 * @param [string] $host
	 * @param [string] $user_name
	 * @param [string] $password
	 * @param [int] $port
	 * @param [int] $timeout
	 */
	public function __construct($host, $user_name, $password, $port = null, $timeout = null)
	{
		$this->host = $host;
		$this->user_name = $user_name;
		$this->password = $password;
		if ($port)
		{
			$this->port = $port;
		}
		if ($timeout)
		{
			$this->timeout = $timeout;
		}

		if (! $this->connection)
		{
			$this->connect();
		}
		return $this;
	}

	public function connect()
	{
		$this->connection = ftp_connect($this->host, $this->port, $this->timeout);
		if (! $this->connection)
		{
			throw new ErrorException('ftp connect failured.');
		}
	}

	public function login()
	{
		if (! $this->connection)
		{
			$this->connect();
		}

		if (! @ftp_login($this->connection, $this->user_name, $this->password))
		{
			throw new ErrorException('ftp login failured.');
		}
	}

	/**
	 * ファイルをダウンロードする
	 * @param  [string] $local_file  ローカルファイルのパス（ファイルがすでに存在する場合は上書きされる）
	 * @param  [string] $remote_file リモートファイルのパス
	 * @param  [constant] $mode      FTP_ASCII or FTP_BINARY
	 * @return [boolean]             ダウンロード成功 true 失敗 false
	 */
	public function get($local_file, $remote_file, $mode = FTP_ASCII)
	{
		$this->login();
		return ftp_get($this->connection, $local_file, $remote_file, $mode);
	}

	/**
	 * FTP 接続を閉じるのに成功したか失敗したかをbooleanで返す
	 * @return [Boolean]
	 */
	public function close()
	{
		return ftp_close($this->connect);
	}

}
