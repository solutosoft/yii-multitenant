<?php

namespace solutosoft\multitenant;

interface TenantInterface {

    /**
     * Returns tenant id
     * @return integer id of tenant
     */
    public function getTenantId();

}
