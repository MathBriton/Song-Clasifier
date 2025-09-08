import React from 'react';

const Header: React.FC = () => {
  return (
    <header className="relative text-white text-center py-8 px-4 bg-cover bg-center" 
            style={{ backgroundImage: "url('/background.jpg')" }}>
      <div className="absolute inset-0 bg-gradient-to-r from-[rgba(139,69,19,0.9)] to-[rgba(210,105,30,0.85)] z-0"></div>
      
      <div className="relative z-10">
        <img 
          src="/tiao-carreiro-pardinho.png" 
          alt="Tião Carreiro" 
          className="w-40 h-40 md:w-48 md:h-48 mx-auto mb-6 rounded-full border-4 border-white/80 shadow-lg" 
        />
        
        <h1 className="text-2xl md:text-3xl font-bold mb-2 text-shadow">
          Top 5 Músicas Mais Tocadas
        </h1>
        
        <h2 className="text-xl md:text-2xl opacity-90 text-shadow">
          Tião Carreiro & Pardinho
        </h2>
      </div>
    </header>
  );
};

export default Header;