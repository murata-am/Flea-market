<template>
    <div>
        <button @click="toggleLike" class="like-btn">
            <span v-if="liked">❤️</span>
            <span v-else>♡</span>
        </button>
        <span class="like-count">{{ likeCount }}</span>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['item_id', 'my_like_check', 'like_num'],
    data() {
        return {
            liked: this.my_like_check,
            likeCount: this.like_num
        };
    },
    methods: {
        async toggleLike() {
            try {
                const response = await axios.post('/like/toggle', {
                    item_id: this.item_id
                });

                this.liked = response.data.liked;
                this.likeCount = response.data.like_count;
            } catch (error) {
                console.error('いいねの処理に失敗しました。', error);
            }
        }
    }
};
</script>

<style scoped>
.like-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

.like-count {
    margin-left: 5px;
    font-size: 18px;
}
</style>
