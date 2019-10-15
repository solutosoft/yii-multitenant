<?php

namespace solutosoft\multitenant;

interface TenantInterface {

    const ATTRIBUTE_NAME = 'tenant_id';

    /**
     * Returns tenant id
     * @return integer id of tenant
     */
    public function getTenantId();

}
