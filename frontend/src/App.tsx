import './App.css'
import LocationList from './components/LocationList';

function App() {
  return (
    <div style={styles.app}>
      <header style={styles.header}>
        <h1>Gestión de Sedes</h1>
      </header>
      <main style={styles.main}>
        <LocationList />
      </main>
    </div>
  );
}

const styles: Record<string, React.CSSProperties> = {
  app: {
    fontFamily: "Arial, sans-serif",
    minHeight: "100vh",
    backgroundColor: "#f5f5f5",
  },
  header: {
    backgroundColor: "#1976d2",
    color: "white",
    padding: "10px 20px",
  },
  main: {
    padding: "20px",
  },
};

export default App;
