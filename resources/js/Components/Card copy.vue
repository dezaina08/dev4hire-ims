<template>
    <div>
        <div class="p-4 lg:p-6 bg-white rounded-lg shadow">
            <div class="pb-4 rounded-t border-b mb-4">
                <slot name="card-header"></slot>
            </div>
            <div>
                <slot name="card-body"></slot>
            </div>
            <div>
                <slot name="card-footer"></slot>
            </div>
        </div>
    </div>
</template>
<script>
export default {

}
</script>
<style lang="">

</style>
