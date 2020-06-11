<template>
    <div>
        <h1>👽 {404}</h1>
        <router-link :to="{name: 'home'}" tag="button" class="el-button el-button--mini">
            <i class="el-icon-s-home"></i> {{ __('at_home') }}
        </router-link>
    </div>
</template>