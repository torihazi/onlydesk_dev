<?php

/**
 * APIレスポンス 基底クラス
 *
 */
abstract class BaseResponse {

	public $code;

	public $error_message;

}

/**
 * APIレスポンス クラス
 *
 */
class Response Extends BaseResponse {

	public $abc;

}