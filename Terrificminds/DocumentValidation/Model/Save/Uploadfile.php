<?php

namespace Terrificminds\DocumentValidation\Model\Save;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Terrificminds\DocumentValidation\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;


class Uploadfile
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * Undocumented variable
     *
     * @var \Terrificminds\DocumentValidation\Helper\Data
     */
    protected $helperData;
  /**
   * Undocumented function
   *
   * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
   * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
   * @param \Magento\Framework\Filesystem $fileSystem
   * @param \Terrificminds\DocumentValidation\Helper\Data $helperData
   */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $fileSystem,
        \Terrificminds\DocumentValidation\Helper\Data $helperData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->uploaderFactory = $uploaderFactory;
        $this->fileSystem = $fileSystem;
        $this->helperData = $helperData;
    }

    /**
     * @param  array $uploadData
     * @return array
     */
    public function uploadFileAndGetInfo($uploadData)
    {
        $allowedExtensions = $this->helperData->getFileExtention();
        $varDirectoryPath = $this->fileSystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath("document");
        $result = $this->uploaderFactory
            ->create(['fileId' => $uploadData])
            ->setAllowedExtensions(explode(',', $allowedExtensions))
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(false)
            ->save($varDirectoryPath);
        return $result;
    }
}
