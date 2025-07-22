import { useEffect, useState } from "react";
export default function AdminDashboard() {
  const [users, setUsers] = useState<any[]>([]);
  const [links, setLinks] = useState<any[]>([]);
  useEffect(() => {
    fetch("/api/admin/users", { credentials: "include" }).then(r => r.json()).then(d => setUsers(d.users || []));
    fetch("/api/admin/urls", { credentials: "include" }).then(r => r.json()).then(d => setLinks(d.urls || []));
  }, []);
  return (
    <div className="w-full mt-8">
      <h2 className="text-xl font-bold mb-4 text-slate-900">Admin Dashboard</h2>
      <div className="mb-8">
        <h3 className="font-bold mb-2">All Users</h3>
        <table className="min-w-full text-sm border border-slate-200 rounded-lg">
          <thead><tr className="bg-slate-100"><th>Email</th><th>Created</th></tr></thead>
          <tbody>{users.map(u => <tr key={u.id}><td>{u.email}</td><td>{new Date(u.created_at).toLocaleString()}</td></tr>)}</tbody>
        </table>
      </div>
      <div>
        <h3 className="font-bold mb-2">All Links</h3>
        <table className="min-w-full text-sm border border-slate-200 rounded-lg">
          <thead><tr className="bg-slate-100"><th>Code</th><th>URL</th><th>User</th><th>Created</th></tr></thead>
          <tbody>{links.map(l => <tr key={l.id}><td>{l.code}</td><td>{l.url}</td><td>{l.user_id}</td><td>{new Date(l.created_at).toLocaleString()}</td></tr>)}</tbody>
        </table>
      </div>
    </div>
  );
} 