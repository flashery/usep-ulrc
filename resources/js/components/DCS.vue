<template>
    <div class="inner-cont">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
        >
            <h1 class="h2">List of (Departments, Courses and Subjects)</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <el-button-group>
                    <el-button
                        :disabled="!authenticated_user.can_edit"
                        type="primary"
                        icon="el-icon-school"
                        @click="addNewDepartment"
                    >New Department</el-button>
                    <el-button
                        :disabled="!authenticated_user.can_edit"
                        type="primary"
                        icon="el-icon-notebook-1"
                        @click="addNewCourse"
                    >New Course</el-button>
                    <el-button
                        :disabled="!authenticated_user.can_edit"
                        type="primary"
                        icon="el-icon-notebook-2"
                        @click="addNewSubject"
                    >New Subject</el-button>
                </el-button-group>
            </div>
        </div>
        <h4 class="mb-3">Departments</h4>
        <departments
         :authenticated_user="authenticated_user"
            @edit-department="editDepartment"
            @edit-course="editCourse"
            @deleted="departmentDeleted"
            @view-subjects="viewSubjects"
            :p_deparments="departments"
            v-loading="load_departments"
        ></departments>

         <department-modal
            v-if="authenticated_user.can_edit"
            @close="department_dlg_show = false"
            @added="departmentAdded"
            @updated="departmentUpdated"
            @deleted="departmentDeleted"
            :mode="mode"
            :p_department="current_deparment"
            :department_dlg_show="department_dlg_show"
        ></department-modal>
        
        <course-modal
            v-if="authenticated_user.can_edit"
            @close="course_dlg_show = false"
            @added="courseAdded"
            @updated="courseUpdated"
            @deleted="courseDeleted"
            :mode="mode"
            :p_course="current_course"
            :course_dlg_show="course_dlg_show"
            :p_departments="departments"
        ></course-modal>

        <subject-modal
            v-if="authenticated_user.can_edit"
            @close="subject_dlg_show = false"
            @added="subjectAdded"
            @updated="subjectUpdated"
            @deleted="subjectDeleted"
            :mode="mode"
            :p_subject="current_subject"
            :subject_dlg_show="subject_dlg_show"
            :p_departments="departments"
        ></subject-modal>
        
        <view-subjects-modal
            @close="view_subject_dlg_show = false"
            @edit-subject="editSubject"
            @deleted="subjectDeleted"
            :authenticated_user="authenticated_user"
            :p_department="current_deparment"
            :p_course="current_course"
            :p_subjects="subjects"
            :view_subject_dlg_show="view_subject_dlg_show"
        ></view-subjects-modal>
    </div>
</template>

<script>
import Departments from "./dcs/Departments";
import SubjectModal from "./dcs/SubjectModal.vue";
import DepartmentModal from "./dcs/DepartmentModal";
import CourseModal from "./dcs/CourseModal";
import ViewSubjectsModal from "./dcs/ViewSubjectsModal.vue";

const MODE_CREATE = "CREATE";
const MODE_UPDATE = "UPDATE";

export default {
    components: {
        Departments,
        SubjectModal,
        DepartmentModal,
        CourseModal,
        ViewSubjectsModal
    },
    data() {
        return {
            authenticated_user: window.Laravel.user,
            mode: "CREATE",
            load_departments: false,
            department_dlg_show: false,
            course_dlg_show: false,
            subject_dlg_show: false,
            view_subject_dlg_show: false,
            departments: [],
            subjects: [],
            current_deparment: { image: "", name: "", description: "" },
            current_course: { name: "", description: "" },
            current_subject: {
                department: {
                    courses: []
                },
                code: "",
                name: "",
                description: ""
            }
        };
    },

    mounted() {
        this.getDepartments();
    },

    methods: {
        // Departments
        getDepartments() {
            this.load_departments = true;
            axios
                .get("/department")
                .then(response => {
                    this.load_departments = false;
                    this.departments = response.data;
                })
                .catch(err => {
                    this.loading = false;

                    this.$message({
                        message:
                            "Oops, there is an error updating user profile image.",
                        type: "error"
                    });
                });
        },
        addNewDepartment() {
            this.mode = MODE_CREATE;
            this.current_deparment = { image: "", name: "", description: "" };
            this.department_dlg_show = true;
        },
        editDepartment(department) {
            this.mode = MODE_UPDATE;
            this.department_dlg_show = true;
            console.log(department);
            this.current_deparment = department;
        },
        departmentAdded() {
            this.getDepartments();
        },
        departmentUpdated() {
            this.getDepartments();
        },
        departmentDeleted() {
            this.getDepartments();
        },

        // Courses
        addNewCourse() {
            this.mode = MODE_CREATE;
            this.course_dlg_show = true;
            this.current_course = {
                departments: [],
                name: "",
                description: ""
            };
        },
        editCourse(course) {
            this.mode = MODE_UPDATE;
            this.current_course = course;
            this.course_dlg_show = true;
        },
        courseAdded() {
            this.getDepartments();
        },
        courseUpdated() {
            this.getDepartments();
        },
        courseDeleted() {
            this.getDepartments();
        },
        // Subject
        addNewSubject() {
            this.mode = MODE_CREATE;
            this.subject_dlg_show = true;
            this.current_subject = {
                department: {
                    courses: []
                },
                code: "",
                name: "",
                description: ""
            };
        },
        editSubject(subject) {
            this.mode = MODE_UPDATE;
            this.current_subject = subject;
            this.subject_dlg_show = true;
        },

        subjectAdded() {
            this.getDepartments();
        },
        subjectUpdated() {
            this.getDepartments();
        },
        subjectDeleted() {
            this.getDepartments();
        },
        viewSubjects(course) {
            this.current_deparment = this.departments.find(department => {
                return department.id === course.department_id;
            });
            this.current_course = course;
            this.subjects = this.current_course.subjects;
            this.view_subject_dlg_show = true;
        }
    }
};
</script>

<style>
</style>
