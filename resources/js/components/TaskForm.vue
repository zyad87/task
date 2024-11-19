<template>
  <div class="modal-container">
      <div class="modal-content">
          <div class="modal-header">
              <h2 class="text-center">
                {{ currentLanguage === 'en' ? (task ? 'Edit Task' : 'Add New Task') : (task ? 'تعديل المهمة' : 'إضافة مهمة جديدة') }}
              </h2>
              <button @click="closeModal" class="close-button">&times;</button>
          </div>
          <form ref="taskForm" @submit.prevent="submitTask" class="p-4 rounded bg-white needs-validation" novalidate>
              <div class="mb-3">
                  <label for="taskName" class="form-label">{{ currentLanguage === 'en' ? 'Task Name' : 'اسم المهمة' }}</label>
                  <input
                      v-model="taskName"
                      id="taskName"
                      type="text"
                      class="form-control"
                      :placeholder="currentLanguage === 'en' ? 'Enter task name' : 'أدخل اسم المهمة'"
                      required
                  />
                  <div class="invalid-feedback">
                      {{ currentLanguage === 'en' ? 'Please enter a task name.' : 'يرجى إدخال اسم المهمة.' }}
                  </div>
              </div>

              <div class="mb-3">
                  <label for="taskDescription" class="form-label">{{ currentLanguage === 'en' ? 'Task Description (optional)' : 'وصف المهمة (اختياري)' }}</label>
                  <textarea
                      v-model="taskDescription"
                      id="taskDescription"
                      class="form-control"
                      :placeholder="currentLanguage === 'en' ? 'Enter task description' : 'أدخل وصف المهمة'"
                      rows="2"
                      maxlength="100"
                  ></textarea>
              </div>

              <div class="task-status-container mb-3">
                  <label for="taskStatus" class="form-label">{{ currentLanguage === 'en' ? 'Task Status' : 'حالة المهمة' }}</label>
                  <select v-model="taskStatus" id="taskStatus" class="form-select" required>
                      <option value="" disabled>{{ currentLanguage === 'en' ? 'Select status' : 'اختر الحالة' }}</option>
                      <option value="Pending">{{ currentLanguage === 'en' ? '⏳ Pending' : '⏳ قيد الانتظار' }}</option>
                      <option value="In Progress">{{ currentLanguage === 'en' ? '🔄 In Progress' : '🔄 قيد التنفيذ' }}</option>
                      <option value="Completed">{{ currentLanguage === 'en' ? '✅ Completed' : '✅ مكتملة' }}</option>
                  </select>
                  <div class="invalid-feedback">
                      {{ currentLanguage === 'en' ? 'Please select a task status.' : 'يرجى اختيار حالة المهمة.' }}
                  </div>
              </div>

              <div class="d-grid">
                  <button type="submit" class="btn btn-primary">
                    {{ currentLanguage === 'en' ? (task ? 'Update Task' : 'Add Task') : (task ? 'تحديث المهمة' : 'إضافة المهمة') }}
                  </button>
              </div>
          </form>
      </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  props: {
      task: {
          type: Object,
          default: null,
      }
  },
  data() {
      return {
          submitted: false, 
          taskName: this.task ? this.task.name : '',
          taskDescription: this.task ? this.task.description : '',
          taskStatus: this.task ? this.task.status : 'Pending',
          maxDescriptionLength: 100,
          currentLanguage: localStorage.getItem('language') || 'en' // جلب اللغة من التخزين المحلي أو افتراضيًا الإنجليزية
      };
  },
  methods: {
    ...mapActions(['createTask', 'updateTask']),
    
    async submitTask() {
        // تحقق من صحة النموذج قبل الإرسال
        const form = this.$refs.taskForm;
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return; // إذا لم يمر التحقق، لا تتابع الإرسال
        }

        try {
            if (this.task) {
                await this.updateTask({
                    id: this.task.id,
                    name: this.taskName,
                    description: this.taskDescription,
                    status: this.taskStatus,
                });
            } else {
                await this.createTask({
                    name: this.taskName,
                    description: this.taskDescription,
                    status: this.taskStatus,
                });
            }
            this.$emit('task-added');
            this.closeModal();
        } catch (error) {
            console.error('Failed to save task:', error);
        }
    },
    
    closeModal() {
        this.$emit('close');
    },
  }
};
</script>

<style scoped>
.modal-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  overflow-y: auto;
  z-index: 1000;
}

.modal-content {
  max-width: 600px;
  width: 100%;
  background-color: #ffffff;
  border-radius: 15px;
  box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
  padding: 30px;
  position: relative;
  animation: fadeIn 0.2s ease;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

h2 {
  color: #333;
  text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
  cursor: pointer;
  transition: color 0.3s ease;
}

.close-button:hover {
  color: #ff5e5e;
}

.form-control,
.form-select {
  border-radius: 5px;
  box-shadow: inset 0px 1px 3px rgba(0, 0, 0, 0.1);
}

input,
textarea,
select {
  font-size: 1rem;
}

.btn-primary {
  color: #4CAF50; /* لون النص */
  background-color: #e0faed; /* لون الخلفية */
  font-weight: bold;
  padding: 10px 20px; /* زيادة المسافة الداخلية لجعل الزر أكبر */
  border: none; /* إضافة إطار بلون متناسق */
  border-radius: 8px; /* زوايا دائرية لإعطاء مظهر ناعم */
  transition: all 0.3s ease; /* تأثير متناسق للتحولات */
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* ظل خفيف للزر */
}

.btn-primary:hover {
  background-color: #d2ffe9; /* تغيير لون الخلفية عند التحويم */
  color: #388e3c; /* لون نص أغمق للتحويم */
  box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15); /* تعميق الظل عند التحويم */
  transform: translateY(-2px); /* تحريك الزر للأعلى قليلاً عند التحويم */
}

.btn-primary:focus {
  background-color: #c2f5da; /* لون الخلفية عند التركيز */
  color: #388e3c; /* لون نص أغمق للتركيز */
  outline: none; /* إزالة الإطار الافتراضي للتركيز */
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@media (max-width: 768px) {
  .modal-content {
    padding: 20px;
    max-width: 90%;
  }
  
  .close-button {
    font-size: 1.2rem;
  }
  
  h2 {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .modal-content {
    padding: 15px;
    max-width: 95%;
  }

  h2 {
    font-size: 1.3rem;
  }

  .btn-primary {
    font-size: 0.9rem;
    padding: 8px;
  }

  .form-control,
  .form-select {
    font-size: 0.9rem;
  }
}
</style>
