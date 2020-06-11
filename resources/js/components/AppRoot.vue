<template>
    <div id="rpac">
            <div id="nav-menu">
                <nav-menu></nav-menu>
            </div>
            <div class="content">
                <router-view></router-view>
            </div>
        </div>
</template>

<script>
    import NavMenu from './NavMenu';

    export default {
        name: 'app-root',
        components: {
            NavMenu
        }
    }
</script>

<style>
    #rpac .el-dialog__headerbtn .el-icon-close {
        font-size: 32px;
    }
    #rpac .el-dialog__title {
        font-size: 24px;
    }
    .index-my .el-form--label-top .el-form-item__label {
        padding: 0 0 0px;
        line-height: 24px;
        color: #888;
        font-size: 12px;
    }
    .table-wrapper {
        max-height: calc(100vh - 80px);
        margin: 8px 0;
        overflow: hidden auto;
    }
    .table-actions {
        display: flex;
    }
    .table-actions__search {
        width: calc(100vw - 160px);
    }
    .table-actions__create {
        width: 150px;
        text-align: right;
        margin-left: auto;
    }
    #nav-menu {
        position: fixed;
        left: 0;
        top: 0;
        bottom: auto;
        width: 48px;
        height: 100vh;
        overflow: hidden auto;
        transition: .3s all;
    }
    .content {
        padding: 8px;
        margin-left: 80px;
    }
    @media screen and (max-width: 720px) {
        .table-wrapper {
            max-height: calc(100vh - 120px);
        }
        #nav-menu {
            bottom: 0;
            top: auto;
            width: 100vw;
            height: 48px;
            overflow: auto hidden;
            transition: .3s all;
        }
        .content {
            margin-left: 0px;
            margin-bottom: 80px;
            height: calc(100vh - 96px);
        }
    }
    @media screen and (min-width: 1080px) {
        #nav-menu {
            width: 200px;
        }
        .content {
            margin-left: 220px;
        }
    }
</style>