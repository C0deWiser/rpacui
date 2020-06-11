<template>
    <ul class="nav-menu">
        <li v-for="r in links" :key="r.name" class="nav-menu__item">
            <router-link :to="{name: r.name}" tag="button" class="el-button nav-menu__link" :class="{'el-button--primary': r.name===$route.name}">
                <i :class="r.meta.icon" class="nav-menu__icon"></i>
                <span class="nav-menu__label">{{ __(r.name) }}</span>
            </router-link>
        </li>
        <li v-if="appUrl" class="nav-menu__item" style="margin-top: auto;">
            <el-button @click="toApp" class="nav-menu__link" type="success" plain>
                <i class="nav-menu__icon el-icon-position"></i>
                <span class="nav-menu__label">{{ __('to_app') }}</span>
            </el-button>
        </li>
    </ul>
</template>

<script>
export default {
    name: 'nav-menu',
    data() {
        return {
            links: this.$routes.filter(r => r.meta && r.meta.icon) || [],
            appUrl: window.appUrl || false
        }
    },
    methods: {
        toApp() {
            if(this.appUrl) location.href = this.appUrl;
            else this.$alert(this.__('app_link_error'));
        }
    }
}
</script>

<style scoped>
    .nav-menu {
        list-style: none;
        height: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        flex-flow: column;
        justify-content: normal;
        
    }
    .nav-menu__item {
        margin: 8px;
    }
    .nav-menu__link {
        padding: 8px;
    }
    .nav-menu__icon {
        font-size: 20px;
        font-weight: bold;
    }
    .nav-menu__label {
        display: none;
    }

    @media screen and (max-width: 720px) {
        .nav-menu {
            flex-flow: wrap;
            justify-content: space-evenly;
        }
    }

    @media screen and (min-width: 1080px) {
        .nav-menu__link {
            display: flex;
            width: 100%;
        }
        .nav-menu__label {
            display: inline-block;
            align-self: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
</style>