import React from "react";

type Props = {
  user: string | null;
  isAdmin: boolean;
  onLogin: () => void;
  onRegister: () => void;
  onLogout: () => void;
  onDashboard: () => void;
  onAdmin: () => void;
  onHome: () => void;
};

export default function Sidebar({ user, isAdmin, onLogin, onRegister, onLogout, onDashboard, onAdmin, onHome }: Props) {
  return (
    <aside className="h-screen w-64 bg-white border-r border-slate-200 flex flex-col py-8 px-4 shadow-lg">
      <div className="mb-10 flex items-center justify-center">
        <span className="font-extrabold text-orange-600 text-2xl cursor-pointer" onClick={onHome}>Shortly</span>
      </div>
      <nav className="flex-1 flex flex-col gap-2">
        <button onClick={onHome} className="text-left px-4 py-2 rounded-lg hover:bg-orange-50 font-semibold text-slate-700">Home</button>
        {user && <button onClick={onDashboard} className="text-left px-4 py-2 rounded-lg hover:bg-orange-50 font-semibold text-slate-700">Dashboard</button>}
        {isAdmin && <button onClick={onAdmin} className="text-left px-4 py-2 rounded-lg hover:bg-orange-50 font-semibold text-slate-700">Admin</button>}
      </nav>
      <div className="mt-auto flex flex-col gap-2">
        {user ? (
          <button onClick={onLogout} className="w-full px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-bold">Logout</button>
        ) : (
          <>
            <button onClick={onLogin} className="w-full px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-bold">Login</button>
            <button onClick={onRegister} className="w-full px-4 py-2 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold">Register</button>
          </>
        )}
      </div>
    </aside>
  );
} 