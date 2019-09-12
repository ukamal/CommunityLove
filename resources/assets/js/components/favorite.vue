<template>
    <a :class="classes" @click.prevent="toggle">
        <i class="fas fa-star fa-2x"></i>
        <span class="faborite">
            {{count}}
        </span>
    </a>
</template>

<script>
    export default {
        props:['question'],
        data(){
            return{
                isFavorited:this.question.is_favorited,
                count: this.question.favorites_count,
                signedIn:true,
                id:this.question.id
            }
        },
        computed:{
            classes(){
                return[
                    'favorite','mt-3',
                    ! this.signedIn ? 'off':(this.isFavorited ? 'fabs':'')
                ];
            },
            endpoint(){
                return `/questions/${this.id}/favorites`
            }
        },
        methods:{
            toggle(){
                this.isFavorited ? this.destroy(): this.create();
            },
            destroy(){
                axios.delete(this.endpoint)
                    .then(res=> {
                        this.count--;
                        this.isFavorited = false;
                    });

            },
            create(){
                axios.post(this.endpoint)
                    .then(res=> {
                        this.count++;
                        this.isFavorited = true;
                    });

            }
        }
    }
</script>