import { useEffect, useState } from "react";

type User = {
  id: number;
  email: string;
  created_at: string;
};

type Link = {
  id: number;
  code: string;
  url: string;
  user_id: string;
  created_at: string;
};

export default function AdminDashboard() {
  const [users, setUsers] = useState<User[]>([]);
  const [links, setLinks] = useState<Link[]>([]);
  useEffect(() => {
    fetch("/api/admin/users", { credentials: "include" }).then(r => r.json()).then(d => setUsers(d.users || []));
    fetch("/api/admin/urls", { credentials: "include" }).then(r => r.json()).then(d => setLinks(d.urls || []));
  }, []);
  return (
    <div className="w-full mt-4 md:mt-8 flex flex-col gap-4 md:gap-6">
      {/* Summary Card */}
      <div className="w-full bg-orange-50 border border-orange-200 rounded-2xl shadow p-4 sm:p-6 flex flex-col items-center mb-2">
        <div className="flex flex-col sm:flex-row gap-6 sm:gap-10">
          <div className="flex flex-col items-center">
            <div className="text-2xl md:text-3xl font-extrabold text-orange-600">{users.length}</div>
            <div className="text-slate-700 font-semibold text-sm md:text-base">Total Users</div>
          </div>
          <div className="flex flex-col items-center">
            <div className="text-2xl md:text-3xl font-extrabold text-orange-600">{links.length}</div>
            <div className="text-slate-700 font-semibold text-sm md:text-base">Total Links</div>
          </div>
        </div>
      </div>
      <div className="w-full bg-white border border-slate-200 rounded-2xl shadow p-2 sm:p-4">
        <h2 className="text-lg md:text-xl font-bold mb-2 md:mb-4 text-slate-900">All Users</h2>
        <div className="overflow-x-auto">
          <table className="min-w-full text-xs md:text-sm border border-slate-200 rounded-lg">
            <thead>
              <tr className="bg-slate-100">
                <th className="px-3 py-2 text-left">Email</th>
                <th className="px-3 py-2 text-left">Created</th>
              </tr>
            </thead>
            <tbody>
              {users.map((u, idx) => (
                <tr key={u.id} className={(idx % 2 === 0 ? "bg-white" : "bg-slate-50") + " border-t border-slate-200 group hover:bg-orange-50 transition-colors duration-150"}>
                  <td className="px-3 py-2">{u.email}</td>
                  <td className="px-3 py-2 whitespace-nowrap">{new Date(u.created_at).toLocaleString()}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
      <div className="w-full bg-white border border-slate-200 rounded-2xl shadow p-2 sm:p-4">
        <h2 className="text-lg md:text-xl font-bold mb-2 md:mb-4 text-slate-900">All Links</h2>
        <div className="overflow-x-auto">
          <table className="min-w-full text-xs md:text-sm border border-slate-200 rounded-lg">
            <thead>
              <tr className="bg-slate-100">
                <th className="px-3 py-2 text-left">Code</th>
                <th className="px-3 py-2 text-left">URL</th>
                <th className="px-3 py-2 text-left">User</th>
                <th className="px-3 py-2 text-left">Created</th>
              </tr>
            </thead>
            <tbody>
              {links.map((l, idx) => (
                <tr key={l.id} className={(idx % 2 === 0 ? "bg-white" : "bg-slate-50") + " border-t border-slate-200 group hover:bg-orange-50 transition-colors duration-150"}>
                  <td className="px-3 py-2 font-mono text-orange-600">{l.code}</td>
                  <td className="px-3 py-2 max-w-xs truncate">{l.url}</td>
                  <td className="px-3 py-2">{l.user_id}</td>
                  <td className="px-3 py-2 whitespace-nowrap">{new Date(l.created_at).toLocaleString()}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
} 