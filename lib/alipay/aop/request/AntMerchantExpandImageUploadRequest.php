<?php
/**
 * ALIPAY API: ant.merchant.expand.image.upload request
 *
 * @author auto create
 * @since 1.0, 2016-07-28 23:35:31
 */
class AntMerchantExpandImageUploadRequest
{
	/** 
	 * 图片二进制字节流
	 **/
	private $imageContent;
	
	/** 
	 * 图片格式
	 **/
	private $imageType;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	private $apiVersion="1.0";
	private $notifyUrl;
	private $returnUrl;
    private $needEncrypt=false;

	
	public function setImageContent($imageContent)
	{
		$this->imageContent = $imageContent;
		$this->apiParas["image_content"] = $imageContent;
	}

	public function getImageContent()
	{
		return $this->imageContent;
	}

	public function setImageType($imageType)
	{
		$this->imageType = $imageType;
		$this->apiParas["image_type"] = $imageType;
	}

	public function getImageType()
	{
		return $this->imageType;
	}

	public function getApiMethodName()
	{
		return "ant.merchant.expand.image.upload";
	}

	public function setNotifyUrl($notifyUrl)
	{
		$this->notifyUrl=$notifyUrl;
	}

	public function getNotifyUrl()
	{
		return $this->notifyUrl;
	}

	public function setReturnUrl($returnUrl)
	{
		$this->returnUrl=$returnUrl;
	}

	public function getReturnUrl()
	{
		return $this->returnUrl;
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

  public function setNeedEncrypt($needEncrypt)
  {

     $this->needEncrypt=$needEncrypt;

  }

  public function getNeedEncrypt()
  {
    return $this->needEncrypt;
  }

}
