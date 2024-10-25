export default {
  name: [(name) => !!name || 'Name is required'],
  email: [
    (email) => !!email || 'Email is required',
    (email) => email.includes('@gmail.com') || 'Enter a valid email address',
  ],
  password: [(password) => !!password || 'Password is required'],
  currentPassword: [
    (currentPassword) => !!currentPassword || 'Current password is required',
  ],
  newPassword: [(newPassword) => !!newPassword || 'New password is required'],
  confirmPassword: [
    (confirmPassword) => !!confirmPassword || 'Confirm password is required',
  ],
};
