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
export default function NavBar({ user, isAdmin, onLogin, onRegister, onLogout, onDashboard, onAdmin, onHome }: Props) {
  return (
    <nav className="w-full flex justify-between items-center py-4 px-6 bg-white shadow">
      <span className="font-extrabold text-orange-600 text-xl cursor-pointer" onClick={onHome}>Shortly</span>
      <div className="flex gap-4 items-center">
        {user && <button onClick={onDashboard} className="text-orange-600 font-bold hover:underline">Dashboard</button>}
        {isAdmin && <button onClick={onAdmin} className="text-orange-600 font-bold hover:underline">Admin</button>}
        {user ? (
          <button onClick={onLogout} className="text-orange-600 font-bold hover:underline">Logout</button>
        ) : (
          <>
            <button onClick={onLogin} className="text-orange-600 font-bold hover:underline">Login</button>
            <button onClick={onRegister} className="text-orange-600 font-bold hover:underline">Register</button>
          </>
        )}
      </div>
    </nav>
  );
} 