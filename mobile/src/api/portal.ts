import { api } from './client';

// ---------------- Student-scoped ----------------
export type TimetableEntry = { day_of_week: number; room: string; subject: string; period: string; start_time: string; end_time: string; teacher: string };
export type TimetableByDay = Record<string, TimetableEntry[]>;

export async function fetchTimetable(studentId: number) {
  const { data } = await api.get(`/students/${studentId}/timetable`);
  return data.data as TimetableByDay;
}

export type AttendanceMark = { id: number; mark_date: string; status: string };
export async function fetchAttendance(studentId: number) {
  const { data } = await api.get(`/students/${studentId}/attendance`);
  return data as { summary: { present: number; total: number; rate: number }; data: AttendanceMark[] };
}

export type Grade = { score: number; comment: string; assessment: string; max_score: number; subject: string };
export async function fetchGrades(studentId: number) {
  const { data } = await api.get(`/students/${studentId}/grades`);
  return data.data as Grade[];
}

export type ReportComment = { grade: string; effort: string; comment: string; subject: string };
export type Report = { id: number; overall_comment: string; generated_at: string; comments: ReportComment[] };
export async function fetchReports(studentId: number) {
  const { data } = await api.get(`/students/${studentId}/reports`);
  return data.data as Report[];
}

export type ConductMark = { id: number; type: 'merit' | 'demerit'; points: number; reason: string; created_at: string };
export async function fetchConduct(studentId: number) {
  const { data } = await api.get(`/students/${studentId}/conduct`);
  return data as { summary: { merits: number; demerits: number }; data: ConductMark[] };
}

export type Homework = {
  id: number; title: string; description: string; due_date: string; subject: string;
  status: string | null; submitted_at: string | null; notes: string | null; submission_id: number | null;
};
export async function fetchHomework(studentId: number) {
  const { data } = await api.get(`/students/${studentId}/homework`);
  return data.data as Homework[];
}

export async function submitHomework(homeworkId: number, notes: string) {
  const { data } = await api.post(`/homework/${homeworkId}/submit`, { notes });
  return data;
}

// ---------------- Finance ----------------
export type Invoice = { id: number; invoice_number: string; total_cents: number; paid_cents: number; status: string; due_date: string };
export async function fetchInvoices() {
  const { data } = await api.get('/invoices');
  return data.data as Invoice[];
}
export async function fetchInvoice(id: number) {
  const { data } = await api.get(`/invoices/${id}`);
  return data.data;
}
export async function payInitiate(invoiceId: number, method: string) {
  const { data } = await api.post('/payments/initiate', { invoice_id: invoiceId, method });
  return data as { ok: boolean; payment_id: number; reference: string; test_mode: boolean; message: string };
}
export async function payVerify(paymentId: number) {
  const { data } = await api.post(`/payments/${paymentId}/verify`);
  return data;
}

// ---------------- Boarding / catering ----------------
export type MenuItem = { id: number; meal: string; description: string; tags: string };
export type MenuDay = { id: number; day_of_week: number; day_name: string; items: MenuItem[] };
export async function fetchMenuWeek() {
  const { data } = await api.get('/menu/week');
  return data.data as MenuDay[];
}
export async function rateMenuItem(itemId: number, rating: number) {
  const { data } = await api.post(`/menu/${itemId}/rate`, { rating });
  return data;
}

export type ExeatRequest = { id: number; reason: string; depart_at: string; return_at: string; status: string };
export async function fetchExeats() {
  const { data } = await api.get('/exeat-requests');
  return data.data as ExeatRequest[];
}
export async function createExeat(payload: { reason: string; depart_at: string; return_at: string }) {
  const { data } = await api.post('/exeat-requests', payload);
  return data;
}

// ---------------- Comms ----------------
export type Thread = { id: number; subject: string; last_message?: { body: string; sender_label: string } };
export async function fetchThreads() {
  const { data } = await api.get('/threads');
  return data.data as Thread[];
}
export type Message = { id: number; body: string; sender_label: string; created_at: string };
export async function fetchThreadMessages(id: number) {
  const { data } = await api.get(`/threads/${id}`);
  return data.data as Message[];
}
export async function sendThreadMessage(id: number, body: string) {
  const { data } = await api.post(`/threads/${id}/messages`, { body });
  return data;
}

// ---------------- Library ----------------
export type CatalogueItem = { id: number; title: string; author: string; copies_total: number };
export async function searchLibrary(q: string) {
  const { data } = await api.get('/library/search', { params: { q } });
  return data.data as CatalogueItem[];
}
export type Loan = { id: number; title: string; author: string; borrowed_at: string; due_at: string; returned_at: string | null };
export async function fetchLoans() {
  const { data } = await api.get('/library/loans');
  return data.data as Loan[];
}

// ---------------- Transport ----------------
export type Route = { id: number; name: string; description: string };
export async function fetchRoutes() {
  const { data } = await api.get('/transport/routes');
  return data.data as Route[];
}
export async function fetchRouteLive(id: number) {
  const { data } = await api.get(`/transport/routes/${id}/live`);
  return data as { simulated: boolean; data: { route: Route; last_ping: any; stops: any[] } };
}

// ---------------- Teacher ----------------
export type TeacherClass = { id: number; name: string; student_count: number };
export async function fetchTeacherClasses() {
  const { data } = await api.get('/teacher/classes');
  return data.data as TeacherClass[];
}
export type ClassStudent = { id: number; first_name: string; last_name: string; admission_number: string };
export async function fetchClassStudents(classId: number) {
  const { data } = await api.get(`/teacher/classes/${classId}/students`);
  return data.data as ClassStudent[];
}
export async function submitAttendanceBulk(classId: number, date: string, marks: { student_id: number; status: string }[]) {
  const { data } = await api.post('/attendance/bulk', { class_id: classId, date, marks });
  return data;
}
export async function submitAssessmentScores(assessmentId: number, scores: { student_id: number; score: number; comment?: string }[]) {
  const { data } = await api.post(`/assessments/${assessmentId}/scores`, { scores });
  return data;
}
export async function createHomework(payload: { class_id: number; subject_id: number; title: string; description: string; due_date: string }) {
  const { data } = await api.post('/homework', payload);
  return data;
}
export async function createConduct(payload: { student_id: number; type: 'merit' | 'demerit'; points: number; reason: string }) {
  const { data } = await api.post('/conduct', payload);
  return data;
}

export type Assessment = { id: number; name: string; max_score: number; subject: string };
export async function fetchClassAssessments(classId: number) {
  const { data } = await api.get(`/teacher/classes/${classId}/assessments`);
  return data.data as Assessment[];
}
export type Subject = { id: number; name: string };
export async function fetchSubjects() {
  const { data } = await api.get('/teacher/subjects');
  return data.data as Subject[];
}
export async function createAssessment(payload: { class_id: number; subject_id: number; name: string; max_score: number }) {
  const { data } = await api.post('/assessments', payload);
  return data as { ok: boolean; id: number };
}
