<template>
    <div :class="['task-list', 'container', 'mt-4', currentLanguage === 'ar' ? 'rtl' : 'ltr']">
        <div class="header d-flex justify-content-between align-items-center">
            <h2 class="task-list-title">
                <i class="fa-solid fa-list-check"></i> {{ currentLanguage === 'en' ? 'Task List' : 'قائمة المهام' }}
            </h2>

            <!-- زر محول اللغة -->
            <button class="language-switcher-btn" @click="toggleLanguage">
                {{ currentLanguage === 'en' ? 'العربية' : 'English' }}
            </button>
        </div>
        <div class="filters mb-4 d-flex justify-content-between align-items-center">
            <!-- خيارات التصفية -->
            <div class="filter-group">
                <label for="filterStatus" class="filter-label">{{ currentLanguage === 'en' ? 'Status:' : 'الحالة:' }}</label>
                <select id="filterStatus" v-model="filterStatus" class="filter-select">
                    <option value="">{{ currentLanguage === 'en' ? 'All' : 'الكل' }}</option>
                    <option value="Pending">{{ currentLanguage === 'en' ? 'Pending' : 'قيد الانتظار' }}</option>
                    <option value="In Progress">{{ currentLanguage === 'en' ? 'In Progress' : 'قيد التنفيذ' }}</option>
                    <option value="Completed">{{ currentLanguage === 'en' ? 'Completed' : 'مكتملة' }}</option>
                </select>
            </div>

            <!-- خيارات الترتيب -->
            <div class="filter-group">
                <label for="sortOrder" class="filter-label">{{ currentLanguage === 'en' ? 'Sort by:' : 'ترتيب حسب:' }}</label>
                <select id="sortOrder" v-model="sortOrder" class="filter-select">
                    <option value="created_asc">{{ currentLanguage === 'en' ? 'Oldest First' : 'الأقدم أولاً' }}</option>
                    <option value="created_desc">{{ currentLanguage === 'en' ? 'Newest First' : 'الأحدث أولاً' }}</option>
                </select>
            </div>

            <!-- زر إضافة مهمة جديدة -->
            <button class="btn add-task-btn" @click="openModal">
                <i class="fa-solid fa-plus"></i> {{ currentLanguage === 'en' ? 'Add Task' : 'إضافة مهمة' }}
            </button>
        </div>

        <!-- قائمة المهام ككروت -->
        <div class="task-cards">
            <div
                v-for="task in filteredAndSortedTasks"
                :key="task.id"
                class="task-card"
            >
                <div class="task-card-header">
                    <h5 class="task-title">{{ task.name }}</h5>
                    <span class="status-icon" :class="statusClass(task.status)">
                        <i :class="statusIcon(task.status)"></i>
                    </span>
                </div>
                <div class="task-card-actions">
                    <button @click="editTask(task)" class="btn btn-edit">
                        <i class="fa-regular fa-pen-to-square"></i> {{ currentLanguage === 'en' ? 'Edit' : 'تعديل' }}
                    </button>
                    <button @click="confirmDelete(task.id)" class="btn btn-delete">
                        <i class="fa-regular fa-trash-can"></i> {{ currentLanguage === 'en' ? 'Delete' : 'حذف' }}
                    </button>
                    <button @click="viewDetails(task)" class="btn btn-details">
                        <i class="fa-regular fa-eye"></i> {{ currentLanguage === 'en' ? 'View' : 'عرض' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- نافذة إضافة/تعديل مهمة -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <TaskForm :task="selectedTask" @task-added="handleTaskAdded" @close="closeModal" />
                    </div>
                </div>
            </div>
        </div>

        <!-- نافذة تفاصيل المهمة -->
        <div v-if="showDetailsModal" class="modal-overlay">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <TaskItem :task="selectedTask" v-if="showDetailsModal" @close="closeDetailsModal" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Swal from 'sweetalert2';
import TaskForm from './TaskForm.vue';
import TaskItem from './TaskItem.vue';

export default {
    components: {
        TaskForm,
        TaskItem,
    },
    data() {
        return {
            showModal: false,
            showDetailsModal: false,
            selectedTask: null,
            filterStatus: '',
            sortOrder: 'created_desc',
            isEditing: false,
            currentLanguage: localStorage.getItem('language') || 'en', // اللغة الحالية يتم جلبها من التخزين المحلي
        };
    },
    computed: {
        ...mapState(['tasks']),
        
        filteredAndSortedTasks() {
            let filteredTasks = this.filterStatus
                ? this.tasks.filter(task => task.status === this.filterStatus)
                : this.tasks;

            return filteredTasks.sort((a, b) => {
                if (this.sortOrder === 'created_asc') {
                    return new Date(a.created_at) - new Date(b.created_at);
                } else {
                    return new Date(b.created_at) - new Date(a.created_at);
                }
            });
        }
    },
    created() {
        this.$store.dispatch('loadTasks').then(() => {
            console.log('Loaded tasks');
        });

        // تحديث اتجاه الصفحة بناءً على اللغة الحالية
        document.documentElement.setAttribute('dir', this.currentLanguage === 'ar' ? 'rtl' : 'ltr');
    },
    methods: {
        toggleLanguage() {
            this.currentLanguage = this.currentLanguage === 'en' ? 'ar' : 'en';
            localStorage.setItem('language', this.currentLanguage); // تخزين اللغة في التخزين المحلي
            document.documentElement.setAttribute('dir', this.currentLanguage === 'ar' ? 'rtl' : 'ltr');
        },
        statusClass(status) {
            switch (status) {
                case 'Pending': return 'icon-pending';
                case 'In Progress': return 'icon-progress';
                case 'Completed': return 'icon-completed';
                default: return 'icon-default';
            }
        },
        statusIcon(status) {
            switch (status) {
                case 'Pending': return 'fa-regular fa-clock';
                case 'In Progress': return 'fa-solid fa-rotate';
                case 'Completed': return 'fa-solid fa-check';
                default: return 'fa-solid fa-question';
            }
        },
        openModal() {
            this.showModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.showModal = false;
            document.body.style.overflow = 'auto';
            this.isEditing = false;
            this.selectedTask = null;
        },
        handleTaskAdded() {
    this.showModal = false;
    document.body.style.overflow = 'auto';

    if (this.isEditing) {
        Swal.fire({
            icon: 'success',
            title: this.currentLanguage === 'en' ? 'Task Updated' : 'تم تحديث المهمة',
            text: this.currentLanguage === 'en' ? 'Your task has been successfully updated!' : 'تم تحديث المهمة بنجاح!',
            showConfirmButton: false,
            timer: 1500,
        });
    } else {
        Swal.fire({
            icon: 'success',
            title: this.currentLanguage === 'en' ? 'Task Added' : 'تم إضافة المهمة',
            text: this.currentLanguage === 'en' ? 'Your task has been successfully added!' : 'تمت إضافة المهمة بنجاح!',
            showConfirmButton: false,
            timer: 1500,
        });
    }
},

        viewDetails(task) {
            this.selectedTask = task;
            this.showDetailsModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeDetailsModal() {
            this.showDetailsModal = false;
            this.selectedTask = null;
            document.body.style.overflow = 'auto';
        },
        confirmDelete(taskId) {
            Swal.fire({
                title: this.currentLanguage === 'en' ? 'Are you sure?' : 'هل أنت متأكد؟',
                text: this.currentLanguage === 'en' ? "You won't be able to revert this!" : 'لن تتمكن من التراجع عن هذا!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: this.currentLanguage === 'en' ? 'Yes, delete it!' : 'نعم، احذفه!',
                cancelButtonText: this.currentLanguage === 'en' ? 'Cancel' : 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('deleteTask', taskId).then(() => {
                        Swal.fire(
                            this.currentLanguage === 'en' ? 'Deleted!' : 'تم الحذف!',
                            this.currentLanguage === 'en' ? 'The task has been deleted.' : 'تم حذف المهمة.',
                            'success'
                        );
                    });
                }
            });
        },
        editTask(task) {
            this.selectedTask = task;
            this.isEditing = true;
            this.openModal();
        },
    },
};
</script>

<style scoped>
body, .rtl {
    font-family: "Tajawal", sans-serif !important;
    direction: rtl;
    text-align: right;
}
.language-switcher-btn{
    font-family: "Tajawal", sans-serif !important;
}

body.ltr {
    font-family: Arial, sans-serif !important;
    direction: ltr;
    text-align: left;
}

/* العنوان */
.task-list-title {
    font-size: 2rem;
    color: #34495e;
    text-align: center;
    margin-bottom: 20px;
    font-weight: 700;
    position: relative;
}

/* تمييز تحت العنوان */
.task-list-title::before {
    content: "";
    position: absolute;
    width: 50px;
    height: 4px;
    background-color: #3498db;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* الفلاتر والتصفية */
.filters {
    background: #ffffff;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-label {
    font-size: 0.9rem;
    font-weight: bold;
    color: #34495e;
}

.filter-select {
    font-size: 0.9rem;
    padding: 0.4rem 1rem;
    border: 1px solid #bdc3c7;
    border-radius: 8px;
    transition: border-color 0.3s ease;
}

.add-task-btn {
    color: #4CAF50;
    border: none;
    background-color: #e0faed;
    font-weight: bold;
    font-size: 1rem;
    padding: 8px 20px;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
    margin-top: 10px;
}

.add-task-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0px 6px 12px rgba(0, 128, 0, 0.4);
}
.add-task-btn:focus {
    color: #4CAF50;
    border: none;
    background-color: #f0fff8;}
.add-task-btn i {
    margin-right: 8px;
}

/* كروت المهام */
.task-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.task-card {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    position: relative;
}

.task-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
}

.task-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.task-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
}

.status-icon {
    font-size: 1rem;
    padding: 0.1rem 0.4rem;
    border-radius: 50%;
}

.icon-pending {
    background-color: rgba(255, 193, 7, 0.15); /* خلفية شفافة مع اللون الغامق */
    color: #FFC107; /* لون النص */
    font-weight: bold;
    backdrop-filter: blur(5px); /* تأثير الغبش */
}
.icon-progress {
    background-color: rgba(3, 169, 244, 0.15);
    color: #03A9F4;
    font-weight: bold;
    backdrop-filter: blur(5px);
}

.icon-completed {
    background-color: rgba(76, 175, 80, 0.15);
    color: #4CAF50;
    font-weight: bold;
    backdrop-filter: blur(5px);
}

.task-card-actions .btn {
    font-size: 0.85rem;
    margin: 0 3px;
    padding: 8px 12px;
    border-radius: 5px;
}

.btn-edit {
    color: #007bff;
    border: none;
    background-color: #f0f8ff;
}
.btn-edit:hover,
.btn-edit:focus  {
    color: #007bff;
    background-color: #e2f2ff;
}
.btn-delete {
    color: #dc3545;
    border: none;
    background-color: #ffe8e8;
}
.btn-delete:hover,
.btn-delete:focus {
    color: #dc3545;
    background-color: #ffd0d0;
}
.btn-details {
    color: #17a2b8;
    border: none;
    background-color: #e0f7fa;
}
.btn-details:hover,
.btn-details:focus {
    color: #17a2b8;
    background-color: #c0f8ff;
}
/* النوافذ المنبثقة */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
    padding: 20px;
    max-width: 600px;
    width: 100%;
    z-index: 1100;
}

.modal-header {
    border-bottom: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-weight: bold;
    color: #333;
}

.btn-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 2rem;
    color: #333;
    cursor: pointer;
    box-shadow: none;
}
.language-switcher {
    display: flex;
    gap: 10px;
}

.language-switcher-btn {
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    background-color: #3498db;
    color: #ffffff;
    font-weight: bold;
}

.language-switcher-btn:hover {
    background-color: #2874a6;
}


/* استعلامات الوسائط للشاشات الصغيرة */
@media (max-width: 768px) {
    .task-list {
        padding: 15px;
    }
    
    .filters {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .add-task-btn {
        width: 100%;
    }

    .task-cards {
        grid-template-columns: 1fr;
    }

    .filter-group {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
}

@media (max-width: 480px) {
    .task-list-title {
        font-size: 1.5rem;
    }

    .task-title {
        font-size: 1rem;
    }

    .add-task-btn {
        font-size: 0.9rem;
        padding: 8px;
    }
}
/* باقي التنسيقات تبقى كما هي */
</style>