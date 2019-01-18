<template>
    <div>
        <Sidebar></Sidebar>
        <div class="page-container">
            <Header></Header>
            <main class="main-content bgc-grey-100">
                <div id="mainContent">
                    <transition name="slide">
                        <router-view></router-view>
                    </transition>
                </div>
            </main>
            <Footer></Footer>
        </div>
    </div>
</template>
<script>
    import Header from './partials/header'
    import Footer from './partials/footer'
    import Sidebar from './partials/sidebar'
    export default {
        components: {Sidebar, Header, Footer}
    }
</script>
