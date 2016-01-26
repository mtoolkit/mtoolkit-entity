<?php 

namespace mtoolkit\entity\model\provider;

interface ReadableProviderInfo extends ReadableProvider {
	public function getSecretKey();
	public function getAppKey();
}
