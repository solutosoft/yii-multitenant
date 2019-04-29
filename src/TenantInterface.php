<?php

namespace Soluto\Multitenant;


interface TenantInterface {

    /**
     * Returns tenant id
     * @return integer id of tenant
     */
    public function getTenantId();

}
