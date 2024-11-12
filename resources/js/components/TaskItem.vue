<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Task Details</h5>
                    <button type="button" class="btn-close" @click="$emit('close')"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body task-details">
                    <p><strong><i class="fa-regular fa-file-lines"></i> Name:</strong> {{ task.name }}</p>
                    <p><strong><i class="fa-solid fa-bars-staggered"></i> Description:</strong> {{ task.description }}</p>
                    <p><strong><i class="fa-solid fa-tag"></i> Status:</strong> <span :class="statusClass(task.status)">{{ task.status }}</span></p>
                    <p><strong><i class="fa-regular fa-clock"></i> Created:</strong> {{ formatTimeAgo(task.created_at) }}</p>
                    <p><strong><i class="fa-solid fa-clock-rotate-left"></i> Updated:</strong> {{ formatTimeAgo(task.updated_at) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { formatDistanceToNow } from 'date-fns';

export default {
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    methods: {
        formatTimeAgo(date) {
            return formatDistanceToNow(new Date(date), { addSuffix: true });
        },
        statusClass(status) {
            switch (status) {
                case 'Pending':
                    return 'badge-pending';
                case 'In Progress':
                    return 'badge-progress';
                case 'Completed':
                    return 'badge-completed';
                default:
                    return 'badge-default';
            }
        }
    }
};
</script>


<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-dialog {
    max-width: 500px;
    width: 90%;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-content {
    padding: 20px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px;
}

.modal-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
}

.btn-close {
    font-size: 1.5rem;
    cursor: pointer;
    background: none;
    border: none;
    box-shadow: none;
    color: #f40303;

}

.task-details p {
    font-size: 1rem;
    margin-bottom: 10px;
    color: #555;
}

.task-details strong {
    font-weight: bold;
    color: #333;
}

.task-details i {
    color: #34495e;
    margin-right: 5px;
}

.status-icon {
    padding: 0.3rem 0.7rem;
    border-radius: 20px;
    color: #fff;
    font-weight: bold;
}

.badge-pending {
    background-color: rgba(255, 193, 7, 0.15); /* خلفية شفافة مع اللون الغامق */
    color: #FFC107; /* لون النص */
    padding: 0.3em 0.8em; /* مساحة داخلية */
    border-radius: 20px; /* شكل بيضاوي */
    font-weight: bold;
    backdrop-filter: blur(5px); /* تأثير الغبش */
}

.badge-progress {
    background-color: rgba(3, 169, 244, 0.15);
    color: #03A9F4;
    padding: 0.3em 0.8em;
    border-radius: 20px;
    font-weight: bold;
    backdrop-filter: blur(5px);
}

.badge-completed {
    background-color: rgba(76, 175, 80, 0.15);
    color: #4CAF50;
    padding: 0.3em 0.8em;
    border-radius: 20px;
    font-weight: bold;
    backdrop-filter: blur(5px);
}
@media (max-width: 768px) {
    .modal-dialog {
        max-width: 90%;
        width: 95%;
        border-radius: 10px;
    }
    
    .modal-title {
        font-size: 1rem;
    }
    
    .btn-close {
        font-size: 1.2rem;
    }
}

</style>

