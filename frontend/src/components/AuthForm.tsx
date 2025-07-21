import { useState } from "react";

type Props = {
  mode: "login" | "register";
  onAuth: (email: string, password: string) => Promise<void>;
  loading: boolean;
};

export default function AuthForm({ mode, onAuth, loading }: Props) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [feedback, setFeedback] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setFeedback("");
    try {
      await onAuth(email, password);
    } catch (err: any) {
      setFeedback(err.message || "Authentication failed");
    }
  };

  return (
    <form className="w-full flex flex-col gap-4" onSubmit={handleSubmit}>
      <input
        type="email"
        className="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-base"
        placeholder="Email"
        value={email}
        onChange={e => setEmail(e.target.value)}
        required
      />
      <input
        type="password"
        className="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-base"
        placeholder="Password"
        value={password}
        onChange={e => setPassword(e.target.value)}
        required
      />
      <button
        type="submit"
        className="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition-colors text-lg flex items-center justify-center gap-2 disabled:opacity-60"
        disabled={loading}
      >
        {loading ? (
          <svg className="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" /></svg>
        ) : null}
        {mode === "login" ? "Log In" : "Register"}
      </button>
      {feedback && <div className="text-center text-red-500 font-semibold mt-2">{feedback}</div>}
    </form>
  );
} 